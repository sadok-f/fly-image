# Flyimg Changelog

## [1.4.6](https://github.com/flyimg/flyimg/compare/1.4.5...1.4.6) (2024-08-29)


### Bug Fixes

* add cleanup cron job to purge tmp folder ([03b46f9](https://github.com/flyimg/flyimg/commit/03b46f9ba76577ed52e64a5d28f470b605350ebe))
* add cleanup cronjob documentation ([087b075](https://github.com/flyimg/flyimg/commit/087b0753b193332194ebd5e788a0e512226c0067))
* add Cleanup cronjob to purge var/tmp folder ([1f44916](https://github.com/flyimg/flyimg/commit/1f44916442d41ff2842da8b466cd2bf4d9eb5b71))
* use latest base image that include cron service ([5bcfa99](https://github.com/flyimg/flyimg/commit/5bcfa99cd15c71f44e6b75dc22ef0e475df66747))

## [1.4.5](https://github.com/flyimg/flyimg/compare/1.4.4...1.4.5) (2024-08-27)


### Bug Fixes

* fixing linting issues ([dd82edb](https://github.com/flyimg/flyimg/commit/dd82edb35d6fd58e09722c538df2d0123a7d0240))
* Redirect URLs without protocol schema are not handled correctly, closes [#486](https://github.com/flyimg/flyimg/issues/486) ([11b785e](https://github.com/flyimg/flyimg/commit/11b785e784d4980997e86899d4f8bdafe3b767f5))

## [1.4.4](https://github.com/flyimg/flyimg/compare/1.4.3...1.4.4) (2024-08-26)


### Bug Fixes

* 483 ([0b8027f](https://github.com/flyimg/flyimg/commit/0b8027fbe63474addb7d390a75b8cd53789e108a))
* 483 ([f50895e](https://github.com/flyimg/flyimg/commit/f50895e25b4e305bee8bcebce37ff2f350cc3c89))

## [1.4.3](https://github.com/flyimg/flyimg/compare/1.4.2...1.4.3) (2024-05-28)


### Bug Fixes

* add css style to version element ([abc0e85](https://github.com/flyimg/flyimg/commit/abc0e855197f6dd33ef8f4ad755930109ee44fab))
* add current version to the home page ([106461f](https://github.com/flyimg/flyimg/commit/106461f4812a39e7ce70ede71ae94ccda2d240d0))
* add missing ambimax/semantic-release-composer library ([12f0640](https://github.com/flyimg/flyimg/commit/12f06403c2e703acae714e99b583e5f36e8cdaac))
* fixing linting issues ([a5423f5](https://github.com/flyimg/flyimg/commit/a5423f53adb510196cd46c913913e3aa157fb3c1))
* fixing the @iwavesmedia/semantic-release-composer lib ([8e2b55a](https://github.com/flyimg/flyimg/commit/8e2b55a9bb51c1e797f3b26cb5cd9d5ea3cc8d52))

## [1.4.2](https://github.com/flyimg/flyimg/compare/1.4.1...1.4.2) (2024-05-28)


### Bug Fixes

* fix default sperator in js code ([ae85361](https://github.com/flyimg/flyimg/commit/ae8536174db6ef353a0e84c057ea3cedc6e2482a))

## [1.4.1](https://github.com/flyimg/flyimg/compare/1.4.0...1.4.1) (2024-05-24)


### Bug Fixes

* add options_separator to js code ([28fb1a0](https://github.com/flyimg/flyimg/commit/28fb1a0ebe940e2ba57e73fb03179d7c5f456015))

# [1.4.0](https://github.com/flyimg/flyimg/compare/1.3.5...1.4.0) (2024-05-10)


### Bug Fixes

* Rename the build image to flyimg/flyimg, run composer update, remove linux/arm/v6 from target platform ([526b3a1](https://github.com/flyimg/flyimg/commit/526b3a10fc2ccd844fe96950e9266a73c132a638))


### Features

* Update README ([b71c09e](https://github.com/flyimg/flyimg/commit/b71c09e93b43306d7ec35e52a478fe4854daa842))

## [1.3.5](https://github.com/flyimg/flyimg/compare/1.3.4...1.3.5) (2024-05-09)


### Bug Fixes

* update flyimg base version to support multi arch build, fixing [#414](https://github.com/flyimg/flyimg/issues/414) ([74d6336](https://github.com/flyimg/flyimg/commit/74d63362b6788a688c6d62742b783324cb9ec7d4))

## [1.3.4](https://github.com/flyimg/flyimg/compare/1.3.3...1.3.4) (2024-02-27)


### Bug Fixes

* adding linux/arm64/v8 architecture ([53c4006](https://github.com/flyimg/flyimg/commit/53c400672269d6492b91c482438cf5da1f189226))

## [1.3.3](https://github.com/flyimg/flyimg/compare/1.3.2...1.3.3) (2024-02-26)


### Bug Fixes

* add Multi-platform image with GitHub Actions, [#414](https://github.com/flyimg/flyimg/issues/414) ([2ab0d07](https://github.com/flyimg/flyimg/commit/2ab0d078e3f8c8eedfb8af682d075bcd7526bb6a))
* fixing Create manifest list stage: ([91524d3](https://github.com/flyimg/flyimg/commit/91524d3276e06080af0cfea91a1737a4504bb706))
* fixing Github Action workflow for Multi-platform Docker image ([1d0e144](https://github.com/flyimg/flyimg/commit/1d0e1444c75917a0dc82523192d3d4ace077c028))
* fixing github workflow ([5aff78a](https://github.com/flyimg/flyimg/commit/5aff78a820025d5351a62090c1d85ef49b94e74b))
* fixing linting errors ([f414470](https://github.com/flyimg/flyimg/commit/f4144703141942e8eb39ebab316d53f0caafabf7))
* fixing missing PLATFORM_PAIR for the articat upload (Github Action CD) ([84b55ff](https://github.com/flyimg/flyimg/commit/84b55ffdb3cc5902208d968c6998cc6b2de48c14))
* fixing Multi-platform image with GitHub Actions ([ce62db0](https://github.com/flyimg/flyimg/commit/ce62db07607989d63af0e08dd4366b0a691b55b3))
* fixing the base image path ([954ff76](https://github.com/flyimg/flyimg/commit/954ff767a03fa48388ab8683d1d24418bb25db76))
* fixing the REGISTRY_IMAGE tags ([eb3d09b](https://github.com/flyimg/flyimg/commit/eb3d09b989a43330ce14741189a743b282b7c6c9))
* fixing typo in docker Create manifest list and push step ([5a7658b](https://github.com/flyimg/flyimg/commit/5a7658bc5cace8825be2b35f2f3de9b447ae183e))
* fixing workflow release issue ([7864655](https://github.com/flyimg/flyimg/commit/7864655f5bc9995796bad0d63cf1e590c2b93ad7))

## [1.3.2](https://github.com/flyimg/flyimg/compare/1.3.1...1.3.2) (2024-02-21)


### Bug Fixes

* fixing facedetect issue for AVIF format images ([543ef80](https://github.com/flyimg/flyimg/commit/543ef80344827dc4a3695aca974ca544826870ff))

## [1.3.1](https://github.com/flyimg/flyimg/compare/1.3.0...1.3.1) (2024-02-18)


### Bug Fixes

* Fix the default value for heic_speed to 8 and make it variable in the config file ([25e7cdd](https://github.com/flyimg/flyimg/commit/25e7cdda5b5df75d1fd9a6816cc764c9f4760693))
* fixing issue with s3 storage system, closing [#325](https://github.com/flyimg/flyimg/issues/325) ([bdb59a0](https://github.com/flyimg/flyimg/commit/bdb59a06ea82eee9c250e7c165e4ccf87b12d42d))

# [1.3.0](https://github.com/flyimg/flyimg/compare/1.2.9...1.3.0) (2023-12-11)


### Bug Fixes

* fix avif test case ([141a2c4](https://github.com/flyimg/flyimg/commit/141a2c49b78a78b5a6e540acb54f6dabb13c8b88))
* fixing the timeout issue when loading source image ([06c6746](https://github.com/flyimg/flyimg/commit/06c6746073be2bcdc5f7b528a64425222559c6f2))


### Features

* add AVIF support ([b56b758](https://github.com/flyimg/flyimg/commit/b56b7586edb7741ce3650f6d62c40dfece80d557))
* use latest docker base image to add AVIF support ([3f13454](https://github.com/flyimg/flyimg/commit/3f13454701d03033a2181b501ca88e5fce057cb1))

## [1.2.9](https://github.com/flyimg/flyimg/compare/1.2.8...1.2.9) (2023-11-29)


### Bug Fixes

* fix demo page ([ee858ec](https://github.com/flyimg/flyimg/commit/ee858ecfa90ff772f80b20fac73e307e465a6666))

## [1.2.8](https://github.com/flyimg/flyimg/compare/1.2.7...1.2.8) (2023-11-27)


### Bug Fixes

* fixing the demo page style ([2c93f15](https://github.com/flyimg/flyimg/commit/2c93f154f8411e6c6f4913304d0769dc0120f1ca))
* improve the demo page style and elements ([8da103f](https://github.com/flyimg/flyimg/commit/8da103f3fcbdf717f85d19506a52f51486d6d77a))

## [1.2.7](https://github.com/flyimg/flyimg/compare/1.2.6...1.2.7) (2023-11-26)


### Bug Fixes

* fix the demo page js error ([87ffe8c](https://github.com/flyimg/flyimg/commit/87ffe8c1041456c0aa49eb977b3dd6961fcd3395))

## [1.2.6](https://github.com/flyimg/flyimg/compare/1.2.5...1.2.6) (2023-11-26)


### Bug Fixes

* fix CD workflow command timeout issue ([7cdca15](https://github.com/flyimg/flyimg/commit/7cdca15540cfa74694ca5a6e27fb5dd6314e31f1))

## [1.2.5](https://github.com/flyimg/flyimg/compare/1.2.4...1.2.5) (2023-11-26)


### Bug Fixes

* add Content-Length header ([a569e3f](https://github.com/flyimg/flyimg/commit/a569e3f66a0b29953ffc695e9362a633136469c2))
* fix dev branch deployment step ([34b714e](https://github.com/flyimg/flyimg/commit/34b714e8fe7bef2ae38c635b6047e0497b76bdc9))

## [1.2.4](https://github.com/flyimg/flyimg/compare/1.2.3...1.2.4) (2023-11-19)


### Bug Fixes

* add dev deployment workflow ([b6d1436](https://github.com/flyimg/flyimg/commit/b6d143618cd1a145b0cef55edbffcbbd2ea04126))
* fixing the wrong definition for the pdf page number, add descriptions for the URL image options for the new Demo page ([8231c6d](https://github.com/flyimg/flyimg/commit/8231c6dfc24ae2711d6732a1b9604efa90b8adf7))

## [1.2.3](https://github.com/flyimg/flyimg/compare/1.2.2...1.2.3) (2023-10-19)


### Bug Fixes

* add disable cache option ([59600ae](https://github.com/flyimg/flyimg/commit/59600ae1c833e98bd87f5b8f6403965666857cdc))

## [1.2.2](https://github.com/flyimg/flyimg/compare/1.2.1...1.2.2) (2023-10-04)


### Bug Fixes

* fix missing composer install cmd ([d8a2073](https://github.com/flyimg/flyimg/commit/d8a207328bdfd881ee49e15252ea2622b934b4fc))

## [1.2.1](https://github.com/flyimg/flyimg/compare/1.2.0...1.2.1) (2023-10-02)


### Bug Fixes

* fix missing variable env. to get the next release version ([6fa51ce](https://github.com/flyimg/flyimg/commit/6fa51ce01e27dda258442b9acb179b8f956d7458))

## [1.2.0](https://github.com/flyimg/flyimg/compare/1.1.58...1.2.0) (2023-10-02)


### Features

* update code to work on php8 and imagemagick 7 ([86a1dc4](https://github.com/flyimg/flyimg/commit/86a1dc4602ad000d6b78cf939477010ca92a2854))

## [1.1.58](https://github.com/flyimg/flyimg/compare/1.1.57...1.1.58) (2023-09-29)

### Bug Fixes

* add set to add a  GitHub release ([511dea9](https://github.com/flyimg/flyimg/commit/511dea943cba6cfe8f5f4ae3d273f97174d43c5a))
* adding release-note publisher ([e18af03](https://github.com/flyimg/flyimg/commit/e18af03c0beb0bd5782e564f8faef035db345122))
* fix changelog file header ([055b7e8](https://github.com/flyimg/flyimg/commit/055b7e85d4003fbcb4519a71be32eed45b52270b))
* fix Changelog title ([2f15f35](https://github.com/flyimg/flyimg/commit/2f15f3584f1a921f5bdc192e2b15d7359da8ae14))

## [1.1.57](https://github.com/flyimg/flyimg/compare/1.1.56...1.1.57) (2023-09-29)

### Bug Fixes (2023-09-29)

* add missing .releaserc file ([b5b8cad](https://github.com/flyimg/flyimg/commit/b5b8cad74881a8278652ba027b085499a87e375e))
* add tagFormat option for semantic-release ([1cb796f](https://github.com/flyimg/flyimg/commit/1cb796f8bde355de3d96f186aa92300b71972ca4))
* update Github token variable enviroment, closes [#363](https://github.com/flyimg/flyimg/issues/363) ([8b8d10a](https://github.com/flyimg/flyimg/commit/8b8d10a82ad5724f46b142ed0a58134d6e45157f))
* use PAT in GitHub action cd workflow ([e7f4da0](https://github.com/flyimg/flyimg/commit/e7f4da0c1370300482c61799108179d107f78f45))
* use Semantic-release for releasing and tagging ([9befb19](https://github.com/flyimg/flyimg/commit/9befb19b77c41fafa2c0a75e84946df3b8c55e07))


## [1.1.22](https://github.com/flyimg/flyimg/tree/1.1.22) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.21...1.1.22)

**Implemented enhancements:**

- Fix Changlelog generator in GitHub action workflow, closes \#287 [\#296](https://github.com/flyimg/flyimg/pull/296) ([sadok-f](https://github.com/sadok-f))

## [1.1.21](https://github.com/flyimg/flyimg/tree/1.1.21) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.20...1.1.21)

**Implemented enhancements:**

- Fix Changlelog generator in GitHub action workflow, fixes issue\#287 [\#295](https://github.com/flyimg/flyimg/pull/295) ([sadok-f](https://github.com/sadok-f))
- Add new workflow for GitHub release action, closes \#287 [\#294](https://github.com/flyimg/flyimg/pull/294) ([sadok-f](https://github.com/sadok-f))

## [1.1.20](https://github.com/flyimg/flyimg/tree/1.1.20) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.19...1.1.20)

## [1.1.19](https://github.com/flyimg/flyimg/tree/1.1.19) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.18...1.1.19)

**Implemented enhancements:**

- create new workflow for GitHub release, closes \#287 [\#293](https://github.com/flyimg/flyimg/pull/293) ([sadok-f](https://github.com/sadok-f))

## [1.1.18](https://github.com/flyimg/flyimg/tree/1.1.18) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.17...1.1.18)

**Implemented enhancements:**

- Fix Changelog generator in Github Action [\#287](https://github.com/flyimg/flyimg/issues/287)
- Fixing changelog generator in GitHub actions, closes \#287 [\#292](https://github.com/flyimg/flyimg/pull/292) ([sadok-f](https://github.com/sadok-f))
- Fixing changelog generator in GitHub actions, closes \#287 [\#291](https://github.com/flyimg/flyimg/pull/291) ([sadok-f](https://github.com/sadok-f))
- Fixing changelog generator in GitHub actions, closes \#287 [\#290](https://github.com/flyimg/flyimg/pull/290) ([sadok-f](https://github.com/sadok-f))
- Fixing changelog generator step in GitHub action, fixes \#287 [\#289](https://github.com/flyimg/flyimg/pull/289) ([sadok-f](https://github.com/sadok-f))
- Fix Changelog generator action step, fixes \#287 [\#288](https://github.com/flyimg/flyimg/pull/288) ([sadok-f](https://github.com/sadok-f))

## [1.1.17](https://github.com/flyimg/flyimg/tree/1.1.17) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.16...1.1.17)

**Implemented enhancements:**

- Fix deployment step in GitHub action, closes \#280 [\#286](https://github.com/flyimg/flyimg/pull/286) ([sadok-f](https://github.com/sadok-f))

## [1.1.16](https://github.com/flyimg/flyimg/tree/1.1.16) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.15...1.1.16)

**Implemented enhancements:**

- Fix deployment action, closes \#280 [\#285](https://github.com/flyimg/flyimg/pull/285) ([sadok-f](https://github.com/sadok-f))

## [1.1.15](https://github.com/flyimg/flyimg/tree/1.1.15) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.14...1.1.15)

**Implemented enhancements:**

- Implement Continuous Deployment \(CD\) for flyimg [\#280](https://github.com/flyimg/flyimg/issues/280)
- Fix deployment action, closes \#280 [\#284](https://github.com/flyimg/flyimg/pull/284) ([sadok-f](https://github.com/sadok-f))

## [1.1.14](https://github.com/flyimg/flyimg/tree/1.1.14) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.13...1.1.14)

**Implemented enhancements:**

- Add deploy to demo server as part of CD, closes \#280 [\#283](https://github.com/flyimg/flyimg/pull/283) ([sadok-f](https://github.com/sadok-f))

## [1.1.13](https://github.com/flyimg/flyimg/tree/1.1.13) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.12...1.1.13)

**Implemented enhancements:**

- Add code checkout step [\#282](https://github.com/flyimg/flyimg/pull/282) ([sadok-f](https://github.com/sadok-f))

## [1.1.12](https://github.com/flyimg/flyimg/tree/1.1.12) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.11...1.1.12)

**Implemented enhancements:**

- Add Docker build/push action when create new release, closes \#280 [\#281](https://github.com/flyimg/flyimg/pull/281) ([sadok-f](https://github.com/sadok-f))

## [1.1.11](https://github.com/flyimg/flyimg/tree/1.1.11) (2020-12-31)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.10...1.1.11)

**Implemented enhancements:**

- Replace Travis-ci by Github Actions [\#279](https://github.com/flyimg/flyimg/pull/279) ([sadok-f](https://github.com/sadok-f))

**Closed issues:**

- Travis-CI issue [\#257](https://github.com/flyimg/flyimg/issues/257)

## [1.1.10](https://github.com/flyimg/flyimg/tree/1.1.10) (2020-12-29)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.9...1.1.10)

**Implemented enhancements:**

- Move to main branch [\#277](https://github.com/flyimg/flyimg/issues/277)
- Update release branch to main [\#278](https://github.com/flyimg/flyimg/pull/278) ([sadok-f](https://github.com/sadok-f))

## [1.1.9](https://github.com/flyimg/flyimg/tree/1.1.9) (2020-12-28)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.8...1.1.9)

**Implemented enhancements:**

- Add Generate ChangeLog to GitHub action [\#275](https://github.com/flyimg/flyimg/pull/275) ([sadok-f](https://github.com/sadok-f))

## [1.1.8](https://github.com/flyimg/flyimg/tree/1.1.8) (2020-12-28)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.7...1.1.8)

**Implemented enhancements:**

- Update changelog [\#274](https://github.com/flyimg/flyimg/pull/274) ([sadok-f](https://github.com/sadok-f))

## [1.1.7](https://github.com/flyimg/flyimg/tree/1.1.7) (2020-12-28)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.6...1.1.7)

**Implemented enhancements:**

- Add Github Action to create new Tag/Release [\#269](https://github.com/flyimg/flyimg/issues/269)
- Add Codecov Report [\#267](https://github.com/flyimg/flyimg/issues/267)
- Downgrade Github action to v5 [\#272](https://github.com/flyimg/flyimg/pull/272) ([sadok-f](https://github.com/sadok-f))
- add empty tag\_prefix for Github actions [\#271](https://github.com/flyimg/flyimg/pull/271) ([sadok-f](https://github.com/sadok-f))
- Add new GitHub action to release tag closes \#269 [\#270](https://github.com/flyimg/flyimg/pull/270) ([sadok-f](https://github.com/sadok-f))
- Add CodeCov Repport, closes \#267 [\#268](https://github.com/flyimg/flyimg/pull/268) ([sadok-f](https://github.com/sadok-f))
- Add Last-Modified headers tag, closes \#261 [\#266](https://github.com/flyimg/flyimg/pull/266) ([sadok-f](https://github.com/sadok-f))

**Closed issues:**

- cache refresh [\#265](https://github.com/flyimg/flyimg/issues/265)
- question about query parameters [\#264](https://github.com/flyimg/flyimg/issues/264)
- Specify a cache validator [\#261](https://github.com/flyimg/flyimg/issues/261)

**Merged pull requests:**

- Bump symfony/http-kernel from 4.4.1 to 4.4.13 [\#262](https://github.com/flyimg/flyimg/pull/262) ([dependabot[bot]](https://github.com/apps/dependabot))
- Bump symfony/http-foundation from 4.4.1 to 4.4.7 [\#254](https://github.com/flyimg/flyimg/pull/254) ([dependabot[bot]](https://github.com/apps/dependabot))

## [1.1.6](https://github.com/flyimg/flyimg/tree/1.1.6) (2020-02-12)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.5...1.1.6)

**Implemented enhancements:**

- Update CHANGELOG.md file [\#251](https://github.com/flyimg/flyimg/issues/251)
- Update CHANGELOG file for the new release [\#249](https://github.com/flyimg/flyimg/issues/249)
- Question - I'm adding support for PDF's and Movies in a fork [\#241](https://github.com/flyimg/flyimg/issues/241)
- Update composer [\#239](https://github.com/flyimg/flyimg/issues/239)
- Update CHANGELOG file, closes \#251 [\#252](https://github.com/flyimg/flyimg/pull/252) ([sadok-f](https://github.com/sadok-f))
- Update changelog file closes \#249 [\#250](https://github.com/flyimg/flyimg/pull/250) ([sadok-f](https://github.com/sadok-f))
- Louisl/feature/movie support [\#246](https://github.com/flyimg/flyimg/pull/246) ([sadok-f](https://github.com/sadok-f))
- Updated readme for PDF and video [\#245](https://github.com/flyimg/flyimg/pull/245) ([louisl](https://github.com/louisl))
- Louisl/feature/movie support, closes \#243 [\#244](https://github.com/flyimg/flyimg/pull/244) ([sadok-f](https://github.com/sadok-f))
- Update composer, fixes \#239 [\#240](https://github.com/flyimg/flyimg/pull/240) ([sadok-f](https://github.com/sadok-f))

**Closed issues:**

- slash error  [\#235](https://github.com/flyimg/flyimg/issues/235)

**Merged pull requests:**

- Updated demo source links [\#247](https://github.com/flyimg/flyimg/pull/247) ([louisl](https://github.com/louisl))

## [1.1.5](https://github.com/flyimg/flyimg/tree/1.1.5) (2019-12-03)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.4...1.1.5)

**Implemented enhancements:**

- Update the dockerfile to use the latest base image 1.1.0 [\#229](https://github.com/flyimg/flyimg/issues/229)
- Update Readme: add Google Cloud Run description [\#227](https://github.com/flyimg/flyimg/issues/227)
- Change the default Nginx port to a variable environment  [\#225](https://github.com/flyimg/flyimg/issues/225)
- Add Cloud Run Button to Flyimg [\#223](https://github.com/flyimg/flyimg/issues/223)
- Refactor ExtractProcessor class [\#221](https://github.com/flyimg/flyimg/issues/221)
- Update CHANGELOG.md [\#219](https://github.com/flyimg/flyimg/issues/219)
- Fixing FaceDetect not working, closes \#236 [\#237](https://github.com/flyimg/flyimg/pull/237) ([sadok-f](https://github.com/sadok-f))
- Update Readme: add Google Cloud Run description, closes \#227 [\#233](https://github.com/flyimg/flyimg/pull/233) ([sadok-f](https://github.com/sadok-f))
- Use new deploy redirector [\#231](https://github.com/flyimg/flyimg/pull/231) ([jamesward](https://github.com/jamesward))
- Update the dockerfile to use the latest base image 1.1.0, closes \#229 [\#230](https://github.com/flyimg/flyimg/pull/230) ([sadok-f](https://github.com/sadok-f))
- Add Cloud Run Button to Flyimg [\#224](https://github.com/flyimg/flyimg/pull/224) ([sadok-f](https://github.com/sadok-f))
- Refactor ExtractProcessor class, closes \#221 [\#222](https://github.com/flyimg/flyimg/pull/222) ([sadok-f](https://github.com/sadok-f))
- Update CHANGELOG.md, closes \#219 [\#220](https://github.com/flyimg/flyimg/pull/220) ([sadok-f](https://github.com/sadok-f))

**Fixed bugs:**

- FaceDetect not working [\#236](https://github.com/flyimg/flyimg/issues/236)

**Merged pull requests:**

- Add changelogs for the new release [\#238](https://github.com/flyimg/flyimg/pull/238) ([sadok-f](https://github.com/sadok-f))
- Add ?git\_repo to cloud-run-button [\#232](https://github.com/flyimg/flyimg/pull/232) ([ahmetb](https://github.com/ahmetb))
- Update FUNDING.yml [\#228](https://github.com/flyimg/flyimg/pull/228) ([sadok-f](https://github.com/sadok-f))

## [1.1.4](https://github.com/flyimg/flyimg/tree/1.1.4) (2019-08-07)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.3...1.1.4)

**Implemented enhancements:**

- Update docker libraries and move to use tags images [\#217](https://github.com/flyimg/flyimg/issues/217)
- Update php minimum version to 7.1 [\#215](https://github.com/flyimg/flyimg/issues/215)
- Fix composer install warning [\#213](https://github.com/flyimg/flyimg/issues/213)
- How to specify a default domain name? [\#211](https://github.com/flyimg/flyimg/issues/211)
- Update the CHANGELOG.md [\#206](https://github.com/flyimg/flyimg/issues/206)
- Update docker libraries and move to use tags images, closes \#217 [\#218](https://github.com/flyimg/flyimg/pull/218) ([sadok-f](https://github.com/sadok-f))
- Update php minimum version to 7.1, closes \#215 [\#216](https://github.com/flyimg/flyimg/pull/216) ([sadok-f](https://github.com/sadok-f))
- Fix composer install warning, closes \#213 [\#214](https://github.com/flyimg/flyimg/pull/214) ([sadok-f](https://github.com/sadok-f))
- Instructions to replace default parameters file [\#212](https://github.com/flyimg/flyimg/pull/212) ([diego-vieira](https://github.com/diego-vieira))
- Update CHANGELOG file, closes \#206 [\#207](https://github.com/flyimg/flyimg/pull/207) ([sadok-f](https://github.com/sadok-f))

**Closed issues:**

- Update the sharpen and unsharp docs. [\#204](https://github.com/flyimg/flyimg/issues/204)

## [1.1.3](https://github.com/flyimg/flyimg/tree/1.1.3) (2019-01-09)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.2...1.1.3)

**Implemented enhancements:**

- Unsharp option doesn't work [\#202](https://github.com/flyimg/flyimg/issues/202)
- Missing User-Agent header to get images [\#200](https://github.com/flyimg/flyimg/issues/200)
- fix Unsharp option and add other options such as sharpen and blur [\#203](https://github.com/flyimg/flyimg/pull/203) ([sadok-f](https://github.com/sadok-f))
- New parameter to add more options to the header, fixes \#200 [\#201](https://github.com/flyimg/flyimg/pull/201) ([sadok-f](https://github.com/sadok-f))
- issue-\#197: Remove the Refresh option in the hash generated for output img [\#198](https://github.com/flyimg/flyimg/pull/198) ([sadok-f](https://github.com/sadok-f))
- issue-\#191: mage path parameters is ignored, closes \#191 [\#193](https://github.com/flyimg/flyimg/pull/193) ([sadok-f](https://github.com/sadok-f))
- issue-\#185: Fix missing demo images on Safari browser, closes \#185 [\#186](https://github.com/flyimg/flyimg/pull/186) ([sadok-f](https://github.com/sadok-f))
- issue-\#176: Update CodeClimate Test Reporter ID \#176 [\#183](https://github.com/flyimg/flyimg/pull/183) ([sadok-f](https://github.com/sadok-f))
- issue-\#176: Update CodeClimate Test Reporter ID \#176 [\#182](https://github.com/flyimg/flyimg/pull/182) ([sadok-f](https://github.com/sadok-f))
- issue-\#176: Update CodeClimate Test Reporter ID \#176 [\#181](https://github.com/flyimg/flyimg/pull/181) ([sadok-f](https://github.com/sadok-f))

**Fixed bugs:**

- Refresh option should be removed from the hashed string for the generated image [\#197](https://github.com/flyimg/flyimg/issues/197)
- A bug with fc and fb options [\#187](https://github.com/flyimg/flyimg/issues/187)
- issue-\#187: A bug with fc and fb options, closes \#187 [\#188](https://github.com/flyimg/flyimg/pull/188) ([sadok-f](https://github.com/sadok-f))

**Merged pull requests:**

- Updated the docs regarding blur, unsharp, and sharpen filters. Fixes \#204 [\#205](https://github.com/flyimg/flyimg/pull/205) ([baamenabar](https://github.com/baamenabar))

## [1.1.2](https://github.com/flyimg/flyimg/tree/1.1.2) (2018-08-03)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.1...1.1.2)

## [1.1.1](https://github.com/flyimg/flyimg/tree/1.1.1) (2017-11-06)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.1.0...1.1.1)

## [1.1.0](https://github.com/flyimg/flyimg/tree/1.1.0) (2017-10-29)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.0.1...1.1.0)

## [1.0.1](https://github.com/flyimg/flyimg/tree/1.0.1) (2017-10-25)

[Full Changelog](https://github.com/flyimg/flyimg/compare/1.0.0...1.0.1)

## [1.0.0](https://github.com/flyimg/flyimg/tree/1.0.0) (2017-06-30)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.2.0...1.0.0)

## [0.2.0](https://github.com/flyimg/flyimg/tree/0.2.0) (2017-06-30)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.9...0.2.0)

## [0.1.9](https://github.com/flyimg/flyimg/tree/0.1.9) (2017-06-30)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.8...0.1.9)

## [0.1.8](https://github.com/flyimg/flyimg/tree/0.1.8) (2017-06-27)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.7...0.1.8)

## [0.1.7](https://github.com/flyimg/flyimg/tree/0.1.7) (2017-06-21)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.6...0.1.7)

## [0.1.6](https://github.com/flyimg/flyimg/tree/0.1.6) (2017-03-28)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.5...0.1.6)

## [0.1.5](https://github.com/flyimg/flyimg/tree/0.1.5) (2017-03-08)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.4...0.1.5)

## [0.1.4](https://github.com/flyimg/flyimg/tree/0.1.4) (2017-03-07)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.3...0.1.4)

## [0.1.3](https://github.com/flyimg/flyimg/tree/0.1.3) (2017-02-18)

[Full Changelog](https://github.com/flyimg/flyimg/compare/0.1.2...0.1.3)

## [0.1.2](https://github.com/flyimg/flyimg/tree/0.1.2) (2017-02-09)

[Full Changelog](https://github.com/flyimg/flyimg/compare/b8b0881092b435f456f87f06d11a219810f897eb...0.1.2)



\* *This Changelog was automatically generated by [github_changelog_generator](https://github.com/github-changelog-generator/github-changelog-generator)*
