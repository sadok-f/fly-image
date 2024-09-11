#!/usr/bin/env python3
# facedetect: a simple face detector for batch processing
# Copyright(c) 2013-2017 by wave++ "Yuri D'Elia" <wavexx@thregr.org>
# Distributed under GPLv2+ (see COPYING) WITHOUT ANY WARRANTY.
from __future__ import print_function, division, generators, unicode_literals

import argparse
import numpy as np
import cv2
import math
import sys
import os

from PIL import Image

try:
    import pillow_avif
except ImportError:
    pass

# CV compatibility stubs
if 'IMREAD_GRAYSCALE' not in dir(cv2):
    # <2.4
    cv2.IMREAD_GRAYSCALE = 0
if 'cv' in dir(cv2):
    # <3.0
    cv2.CASCADE_DO_CANNY_PRUNING = cv2.cv.CV_HAAR_DO_CANNY_PRUNING
    cv2.CASCADE_FIND_BIGGEST_OBJECT = cv2.cv.CV_HAAR_FIND_BIGGEST_OBJECT
    cv2.FONT_HERSHEY_SIMPLEX = cv2.cv.InitFont(cv2.cv.CV_FONT_HERSHEY_SIMPLEX, 0.5, 0.5, 0, 1, cv2.cv.CV_AA)
    cv2.LINE_AA = cv2.cv.CV_AA

    def getTextSize(buf, font, scale, thickness):
        return cv2.cv.GetTextSize(buf, font)

    def putText(im, line, pos, font, scale, color, thickness, lineType):
        return cv2.cv.PutText(cv2.cv.fromarray(im), line, pos, font, color)

    cv2.getTextSize = getTextSize
    cv2.putText = putText

# Profiles
# For local MacOs
# DATA_DIR = '/Users/xxxx/Library/Python/3.9/lib/python/site-packages/cv2/data/'
DATA_DIR = '/usr/share/opencv4/'
CASCADES = {}

PROFILES = {
    'HAAR_FRONTALFACE_ALT2': 'haarcascades/haarcascade_frontalface_alt2.xml',
    # For local MacOs
    # 'HAAR_FRONTALFACE_ALT2': 'haarcascade_frontalface_alt2.xml',
}

# Face normalization
NORM_SIZE = 100
NORM_MARGIN = 10

# Support functions
def error(msg):
    sys.stderr.write("{}: error: {}\n".format(os.path.basename(sys.argv[0]), msg))

def fatal(msg):
    error(msg)
    sys.exit(1)


def load_cascades(data_dir):
    for k, v in PROFILES.items():
        v = os.path.join(data_dir, v)
        try:
            if not os.path.exists(v):
                raise cv2.error('no such file')
            CASCADES[k] = cv2.CascadeClassifier(v)
        except cv2.error:
            fatal("cannot load {} from {}".format(k, v))


def crop_rect(im, rect, shave=0):
    return im[rect[1]+shave:rect[1]+rect[3]-shave,
              rect[0]+shave:rect[0]+rect[2]-shave]


def shave_margin(im, margin):
    return im[margin:-margin, margin:-margin]


def norm_rect(im, rect, equalize=True, same_aspect=False):
    roi = crop_rect(im, rect)
    if equalize:
        roi = cv2.equalizeHist(roi)
    side = NORM_SIZE + NORM_MARGIN
    if same_aspect:
        scale = side / max(rect[2], rect[3])
        dsize = (int(rect[2] * scale), int(rect[3] * scale))
    else:
        dsize = (side, side)
    roi = cv2.resize(roi, dsize, interpolation=cv2.INTER_CUBIC)
    return shave_margin(roi, NORM_MARGIN)


def rank(im, rects):
    scores = []
    best = None

    for i in range(len(rects)):
        rect = rects[i]
        roi_n = norm_rect(im, rect, equalize=False, same_aspect=True)
        roi_l = cv2.Laplacian(roi_n, cv2.CV_8U)
        e = np.sum(roi_l) / (roi_n.shape[0] * roi_n.shape[1])

        dx = im.shape[1] / 2 - rect[0] + rect[2] / 2
        dy = im.shape[0] / 2 - rect[1] + rect[3] / 2
        d = math.sqrt(dx ** 2 + dy ** 2) / (max(im.shape) / 2)

        s = (rect[2] + rect[3]) / 2
        scores.append({'s': s, 'e': e, 'd': d})

    sMax = max([x['s'] for x in scores])
    eMax = max([x['e'] for x in scores])

    for i in range(len(scores)):
        s = scores[i]
        sN = s['sN'] = s['s'] / sMax
        eN = s['eN'] = s['e'] / eMax
        f = s['f'] = eN * 0.7 + (1 - s['d']) * 0.1 + sN * 0.2

    ranks = range(len(scores))
    ranks = sorted(ranks, reverse=True, key=lambda x: scores[x]['f'])
    for i in range(len(scores)):
        scores[ranks[i]]['RANK'] = i

    return scores, ranks[0]


def mssim_norm(X, Y, K1=0.01, K2=0.03, win_size=11, sigma=1.5):
    C1 = K1 ** 2
    C2 = K2 ** 2
    cov_norm = win_size ** 2

    ux = cv2.GaussianBlur(X, (win_size, win_size), sigma)
    uy = cv2.GaussianBlur(Y, (win_size, win_size), sigma)
    uxx = cv2.GaussianBlur(X * X, (win_size, win_size), sigma)
    uyy = cv2.GaussianBlur(Y * Y, (win_size, win_size), sigma)
    uxy = cv2.GaussianBlur(X * Y, (win_size, win_size), sigma)
    vx = cov_norm * (uxx - ux * ux)
    vy = cov_norm * (uyy - uy * uy)
    vxy = cov_norm * (uxy - ux * uy)

    A1 = 2 * ux * uy + C1
    A2 = 2 * vxy + C2
    B1 = ux ** 2 + uy ** 2 + C1
    B2 = vx + vy + C2
    D = B1 * B2
    S = (A1 * A2) / D

    return np.mean(shave_margin(S, (win_size - 1) // 2))


def face_detect(im, biggest=False):
    side = math.sqrt(im.size)
    minlen = int(side / 20)
    maxlen = int(side / 2)
    flags = cv2.CASCADE_DO_CANNY_PRUNING
    # optimize queries when possible
    if biggest:
        flags |= cv2.CASCADE_FIND_BIGGEST_OBJECT

    # frontal faces
    cc = CASCADES['HAAR_FRONTALFACE_ALT2']
    features = cc.detectMultiScale(im, 1.1, 1, flags, (minlen, minlen), (maxlen, maxlen))
    return features


def face_detect_file(path, biggest=False):
    # workaround for AVIF format (cv2 wasn't able to read it)
    if path.endswith('.avif'):
        img = Image.open(path)
        img.save(path + '-tmp.jpg')
        path = path + '-tmp.jpg'
    im = cv2.imread(path, cv2.IMREAD_GRAYSCALE)
    if im is None:
        fatal("cannot load input image {}".format(path))
    im = cv2.equalizeHist(im)
    features = face_detect(im, biggest)
    # remove the tmp file for AVIF format
    if path.endswith('-tmp.jpg'):
        os.remove(path)
    return im, features


def pairwise_similarity(im, features, template, **mssim_args):
    template = np.float32(template) / 255
    for rect in features:
        roi = norm_rect(im, rect)
        roi = np.float32(roi) / 255
        yield mssim_norm(roi, template, **mssim_args)


def __main__():
    ap = argparse.ArgumentParser(description='A simple face detector for batch processing')
    ap.add_argument('--biggest', action="store_true",
                    help='Extract only the biggest face')
    ap.add_argument('--best', action="store_true",
                    help='Extract only the best matching face')
    ap.add_argument('-c', '--center', action="store_true",
                    help='Print only the center coordinates')
    ap.add_argument('--data-dir', metavar='DIRECTORY', default=DATA_DIR,
                    help='OpenCV data files directory')
    ap.add_argument('-q', '--query', action="store_true",
                    help='Query only (exit 0: face detected, 2: no detection)')
    ap.add_argument('-s', '--search', metavar='FILE',
                    help='Search for faces similar to the one supplied in FILE')
    ap.add_argument('--search-threshold', metavar='PERCENT', type=int, default=30,
                    help='Face similarity threshold (default: 30%%)')
    ap.add_argument('-o', '--output', help='Image output file')
    ap.add_argument('-d', '--debug', action="store_true",
                    help='Add debugging metrics in the image output file')
    ap.add_argument('file', help='Input image file')
    args = ap.parse_args()

    load_cascades(args.data_dir)

    # detect faces in input image
    im, non_sorted_features = face_detect_file(args.file, args.query or args.biggest)
    # sort the features
    features = sorted(non_sorted_features, key=lambda x: x[0])

    # match against the requested face
    sim_scores = None
    if args.search:
        s_im, s_features = face_detect_file(args.search, True)
        if len(s_features) == 0:
            fatal("cannot detect face in template")
        sim_scores = []
        sim_features = []
        sim_threshold = args.search_threshold / 100
        sim_template = norm_rect(s_im, s_features[0])
        for i, score in enumerate(pairwise_similarity(im, features, sim_template)):
            if score >= sim_threshold:
                sim_scores.append(score)
                sim_features.append(features[i])
        features = sim_features

    # exit early if possible
    if args.query:
        return 0 if len(features) else 2

    # compute scores
    scores = []
    best = None
    if len(features) and (args.debug or args.best or args.biggest or sim_scores):
        scores, best = rank(im, features)
        if sim_scores:
            for i in range(len(features)):
                scores[i]['MSSIM'] = sim_scores[i]

    # debug features
    if args.output:
        im = cv2.imread(args.file)
        font = cv2.FONT_HERSHEY_SIMPLEX
        fontHeight = cv2.getTextSize("", font, 0.5, 1)[0][1] + 5

        for i in range(len(features)):
            if best is not None and i != best and not args.debug:
                next

            rect = features[i]
            fg = (0, 255, 255) if i == best else (255, 255, 255)

            xy1 = (rect[0], rect[1])
            xy2 = (rect[0] + rect[2], rect[1] + rect[3])
            cv2.rectangle(im, xy1, xy2, (0, 0, 0), 4)
            cv2.rectangle(im, xy1, xy2, fg, 2)

            if args.debug:
                lines = []
                for k, v in scores[i].items():
                    lines.append("{}: {}".format(k, v))
                h = rect[1] + rect[3] + fontHeight
                for line in lines:
                    cv2.putText(im, line, (rect[0], h), font, 0.5, fg, 1, cv2.LINE_AA)
                    h += fontHeight

        cv2.imwrite(args.output, im)

    # output
    if (args.best or args.biggest) and best is not None:
        features = [features[best]]

    if args.center:
        for rect in features:
            x = int(rect[0] + rect[2] / 2)
            y = int(rect[1] + rect[3] / 2)
            print("{} {}".format(x, y))
    else:
        for rect in features:
            print("{} {} {} {}".format(*rect))

    return 0

if __name__ == '__main__':
    sys.exit(__main__())
