const generatedImage = document.getElementById('generated-image');
const generatedImageDiv = document.getElementById('generated-image-div');
const loadingSpinner = document.getElementById('loading-spinner');
const dynamicInputFields = document.getElementById('dynamic-input-fields');
let sourceImageValidation = document.getElementById('source-image-validation');
let sourceImage = document.getElementById('source-image');

// The Separator value should match the options_separator value in config/parameters.yml
const OPTIONS_SEPARATOR = '+'

const isEmpty = str => !str.trim().length;
const addInput = () => {
    const htmlDivElement = document.createElement('div');
    htmlDivElement.className = 'form-group';
    htmlDivElement.innerHTML = `
        <hr/>
        <div class="input-group row">
            <div class="col-md-1">
                <button class="btn btn-outline-secondary" type="button">X</button>
            </div>
            <div class="col-md-2">
                <select class="form-control">
                    <option value="q" data-bs-example="Controls the output quality of generated image, valid values are in the range 0 – 100, default is 90.">quality</option>
                    <option value="o" data-bs-example="The output format to convert the image to, default auto, possible values: auto | input (same as the input image) | webp | avif | jpg | png">output</option>
                    <option value="rf" data-bs-example="It will force a re-request of the original image, default 0 , to enable it 1">refresh</option>
                    <option value="unsh" data-bs-example="Sharpens an image, usage {radius}x{sigma}[+gain][+threshold], examples: 0x6 | 0.25x0.25+8+0.065 ">unsharp</option>
                    <option value="sh" data-bs-example="Use a Gaussian operator of the given radius and standard deviation (sigma), usage {radius}x{sigma}, example: 3 | 0x5">sharpen</option>
                    <option value="fc" data-bs-example="Crop a face from given image, default 0 , to enable it 1">face-crop</option>
                    <option value="fcp" data-bs-example="Can be used combined with Face Crop option, you can specify which face you want get cropped in case of multi faces, example 2">face-crop-position</option>
                    <option value="fb" data-bs-example="Apply blur effect on faces in a given image, default 0 , to enable it 1 ">face-blur</option>
                    <option value="w" data-bs-example="Sets the target width of the image. If not set, width will be calculated in order to keep aspect ratio.">width</option>
                    <option value="h" data-bs-example="Sets the target height of the image. If not set, height will be calculated in order to keep aspect ratio.">height</option>
                    <option value="c" data-bs-example="When both width and height are set, this allows the image to be cropped so it fills the width x height area, default 0 , to enable it 1">crop</option>
                    <option value="bg" data-bs-example="Sets the background of the canvas for the cases where padding is added to the images. It supports hex, css color names, rgb. Only css color names are supported without quotation marks. For the hex code, the hash # character should be replaced by %23, example bg_red,bg_%23ff4455,bg_rgb(255,120,100)">background</option>
                    <option value="st" data-bs-example="Removes exif data and additional color profile. Leaving your image with the default sRGB color profile, default 0 , to enable it 1 ">strip</option>
                    <option value="ao" data-bs-example="Adjusts an image so that its orientation is suitable for viewing (i.e. top-left orientation), default 0 , to enable it 1">auto-orient</option>
                    <option value="rz" data-bs-example="The alternative resizing method to -thumbnail, default 0 , to enable it 1">resize</option>
                    <option value="g" data-bs-example="When crop is applied, changing the gravity will define which part of the image is kept inside the crop area. The basic options are: NorthWest, North, NorthEast, West, Center, East, SouthWest, South, SouthEast.">gravity</option>
                    <option value="f" data-bs-example="Resizing algorithm, default Lanczos, possible value Triangle (Triangle is a smoother lighter option)">filter</option>
                    <option value="r" data-bs-example="Apply image rotation (using shear operations) to the image, default 0, possible value 90 | 45">rotate</option>
                    <option value="sc" data-bs-example="The -scale resize operator is a simplified, faster form of the resize command. Useful for fast exact scaling of pixels, default 0 , to enable it 1">scale</option>
                    <option value="e" data-bs-example="Extract and crop an image with given the x/y coordinates of each booth top and bottom.">extract</option>
                    <option value="p1x" data-bs-example="The Point's 1 x coordinates of the Extract option">extract-top-x</option>
                    <option value="p1y" data-bs-example="The Point's 1 y coordinates of the Extract option">extract-top-y</option>
                    <option value="p2x" data-bs-example="The Point's 2 x coordinates of the Extract option">extract-bottom-x</option>
                    <option value="p2y" data-bs-example="The Point's 2 y coordinates of the Extract option">extract-bottom-y</option>
                    <option value="sf" data-bs-example="This option specifies the sampling factors to be used by the JPEG encoder for chroma downsampling. If this option is omitted, the JPEG library will use its own default values, default 0 to enable it 1">sampling-factor</option>
                    <option value="smc" data-bs-example="This option automatically identifies and extracts the most visually compelling region from an image, default 0 , to enable it 1">smart-crop</option>
                    <option value="ett" data-bs-example="Set the image size and offset, default Null, example values 4:3 | 800x600">extent</option>
                    <option value="par" data-bs-example="If set to 0, when passing width and height to an image, the image will be distorted to fill the size of the rectangle defined by width and height, default 1 ">preserve-aspect-ratio</option>
                    <option value="pns" data-bs-example="f set to 0 and if the source image is smaller than the target dimensions, the image will be stretched to the target size, default 1 ">preserve-natural-size</option>
                    <option value="webpl" data-bs-example="If output is set to webp, it will default to lossy compression, but if you want lossless webp encoding you have to set it to 1, default 0">webp-lossless</option>
                    <option value="gf" data-bs-example="When supplying a Gif image, you can choose which frame to generate the output image from, default 0, possible values the frame number : 1 , 3 , 10,..">gif-frame</option>
                    <option value="pdfp" data-bs-example="When supplying a PDF as input, you can specify a which page number to generate the image from, default 1, possible values one the pdf pages number.">pdf-page-number</option>
                    <option value="tm" data-bs-example="Get a video image to fit dimensions from a time duration point, possible value 00:00:05 ">time</option>
                    <option value="clsp" data-bs-example="Converting to Colorspace Gray, possible value Gray">colorspace</option>
                    <option value="mnchr" data-bs-example="Converting to Monochrome, possible value 1">monochrome</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control option-value" placeholder="Enter value" />
            </div>
            <div class="col-md">
                <div class="p-2 bg-info bg-opacity-10 border-info rounded row">
                    <div class="col-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                          <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                        </svg>
                    </div>
                    <div class="example-info col">Default 90 | Example 80</div>
                </div>
            </div>
        </div>
    `;
    dynamicInputFields.appendChild(htmlDivElement);
    let deleteBtn = htmlDivElement.querySelector('button')
    let inputField = htmlDivElement.querySelector('input')
    deleteBtn.addEventListener('click', function () {
        removeInput(this);
    }, false);
    let selectInput = htmlDivElement.querySelector('select')
    let exampleBox = htmlDivElement.querySelector('div.example-info')

    inputField.focus();
    selectInput.addEventListener('change', function () {
        exampleBox.innerHTML = this.querySelector('option:checked').getAttribute('data-bs-example')
        inputField.focus();
    }, false);

};

const removeInput = button => {
    dynamicInputFields.removeChild(button.closest('.form-group'));
};


const refreshImage = () => {
    let sourceImageInput = document.getElementById('source-image-input');
    let errorOptions = document.getElementById('error-options');

    sourceImage.src = decodeURIComponent(sourceImageInput.value);

    if (isEmpty(sourceImageInput.value)) {
        sourceImageInput.setAttribute('class', 'form-control is-invalid');
        sourceImageValidation.innerHTML = 'Please enter an Image URL.'
        sourceImageValidation.style.display = 'block';
        return;
    }

    //option-value
    sourceImageValidation.style.display = 'none';
    errorOptions.style.display = 'none';
    sourceImageInput.setAttribute('class', 'form-control is-valid');

    const inputs = dynamicInputFields.querySelectorAll('.input-group');

    // Combine input values to form the image parameters
    let imageParams = '';
    inputs.forEach((input, index) => {
        let input_option = input.querySelector('input')
        if (isEmpty(input_option.value)) {
            input_option.setAttribute('class', 'form-control is-invalid');
            return;
        }
        input_option.setAttribute('class', 'form-control is-valid');
        let input_key = input.querySelector('option:checked').value
        imageParams += `${input_key}_${encodeURIComponent(input_option.value)}`+ OPTIONS_SEPARATOR;
    });

    // Get the base image URL
    const baseUrl = window.location.href + 'upload/';

    // Concatenate parameters with commas and append to the base URL
    let params = imageParams.slice(0, -1);
    if (!params) {
        errorOptions.style.display = 'block';
        return;
    }

    const imageUrl = baseUrl + params + '/' + sourceImageInput.value; // Remove the trailing comma
    document.getElementById('generated-url').innerHTML = imageUrl;
    document.getElementById('generated-url-container').style.display = 'block';

    showLoading();

    generatedImage.onload = hideLoading;
    generatedImage.onerror = errorWhileLoading;
    generatedImage.src = imageUrl;

};

const errorWhileLoading = () => {
    // Display loading spinner
    loadingSpinner.style.display = 'none';
    sourceImageValidation.style.display = 'block';
    sourceImageValidation.innerHTML = 'Error while leading the generated image'
};

const showLoading = () => {
    // Display loading spinner
    loadingSpinner.style.display = 'block';
    generatedImageDiv.style.display = 'none';
};
const getImageInformation = () => {
    let sourceElement = document.getElementById('source-image-caption')
    let generatedElement = document.getElementById('generated-image-caption')
    sourceElement.style.display = 'block';
    generatedElement.style.display = 'block';
    let sourceDimension = 'Dimensions: ' + sourceImage.naturalWidth + 'x' + sourceImage.naturalHeight;
    let generateDimension = 'Dimensions: ' + generatedImage.naturalWidth + 'x' + generatedImage.naturalHeight;
    let sourceSize, generatedSize, sourceType, generatedType = '';
    let xhr = new XMLHttpRequest();
    xhr.open('HEAD', generatedImage.src, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                //Generated image
                generatedSize = '<br>Size: ' + formatBytes(xhr.getResponseHeader('Content-Length'));
                generatedType = '<br>Content-Type: ' + xhr.getResponseHeader('Content-Type');
                //Source image
                sourceSize = '<br>Size: ' + formatBytes(xhr.getResponseHeader('Source-Content-Length'));
                sourceType = '<br>Content-Type: ' + xhr.getResponseHeader('Source-Content-Type');
            }
        }
        sourceElement.innerHTML = sourceDimension + sourceSize + sourceType
        generatedElement.innerHTML = generateDimension + generatedSize + generatedType
    };
    xhr.send(null);
};

const hideLoading = () => {
    loadingSpinner.style.display = 'none';
    generatedImageDiv.style.display = 'block';
    setTimeout(function () {
        getImageInformation();
    }, 500);
};

const formatBytes = (bytes, decimals = 2) => {
    if (!+bytes) return '0 Bytes'

    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']

    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
};

// self executing function here
(function () {
    let refreshBtn = document.getElementById('refresh-btn');
    refreshBtn.addEventListener("click", function () {
        refreshImage();
    }, false);

    let addBtn = document.getElementById('add-btn');
    addBtn.addEventListener("click", function () {
        addInput();
    }, false);

    let copyBtn = document.getElementById('copy-btn');
    copyBtn.addEventListener("click", function () {
        let copyText = document.getElementById("generated-url");
        // Copy the text inside the text field
        navigator.clipboard.writeText(copyText.innerHTML);
    }, false);

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
})();