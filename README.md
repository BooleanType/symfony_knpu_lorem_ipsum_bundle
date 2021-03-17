Theory:
-------
- making bundle from the scratch:
  - https://symfonycasts.com/screencast/symfony-bundle
- Symfony docs:
  - https://symfony.com/doc/current/service_container.html
  - https://symfony.com/doc/current/components/dependency_injection.html
  - https://symfony.com/doc/current/components/dependency_injection/compilation.html
  - https://symfony.com/doc/current/service_container/compiler_passes.html
  - https://symfony.com/doc/current/bundles.html
  - https://symfony.com/doc/current/bundles/extension.html
  - https://symfony.com/doc/current/service_container/definitions.html
  - https://symfony.com/doc/current/bundles/configuration.html
  - https://symfony.com/doc/current/configuration/using_parameters_in_dic.html
  - https://symfony.com/doc/current/bundles/prepend_extension.html
  - https://symfony.com/doc/current/bundles/best_practices.html
- @TOREAD:
  - https://habr.com/ru/post/498134/
  - https://habr.com/ru/post/419451/

My bundle on packagist:
-----------------------
https://packagist.org/packages/boolean-type/lorem-ipsum-bundle

See, how it works:
------------------
- in browser:
  - http://first-project/article/show
  - http://first-project/api/ipsum/
- in console:
  - `D:\server\www\LoremIpsumBundle>vendor\bin\simple-phpunit     # Run the tests.`


How to make changes in bundle locally?
--------------------------------------
Changes, made in `vendor` folder, for bundle, installed from packagist (https://packagist.org/packages/boolean-type/lorem-ipsum-bundle) wan't work. So, I need to made them in local (ie this) bundle version.

<h5>Switch from packagist version to local:</h5>

1. `composer remove boolean-type/lorem-ipsum-bundle`

2. In <i>app's</i> `composer.json` add this (`"../LoremIpsumBundle"` is the path, where my bundle locally exists):
<pre>
"repositories": [
    {
        "type": "path",
        "url": "../LoremIpsumBundle"
     }
]
</pre>

3. `composer require boolean-type/lorem-ipsum-bundle:*@dev`

<h5>Switch from local version to packagist:</h5>

1. In <i>app's</i> `composer.json` delete this:
<pre>
"repositories": [
    {
        "type": "path",
        "url": "../LoremIpsumBundle"
     }
]
</pre>

2. `composer remove boolean-type/lorem-ipsum-bundle`

3. `composer req "boolean-type/lorem-ipsum-bundle:^1.0"` (`1.0` is bundle <b>current</b> version)

Recipe
------

This recipe will work only for my bundle on packagist, not for this, local version.

https://github.com/symfony/recipes-contrib/pull/1099

<h3>How did I created recipe, which is accessible in symfony/recipes-contrib?</h3>

I've forked from https://github.com/symfony/recipes-contrib . Next I git-cloned this repo to my disc and created new folder with my recipe. Then I've created a pull request from a fork, as explained here - https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/creating-a-pull-request-from-a-fork . From this time all changes, that I pushed to my fork, automatically pushed to created pull request in symfony/recipes-contrib. After successful validation "View deployment" link appeared. This links contains instructions, described below.

<h3>Clear composer cache before installation:</h3>

`composer clearcache`

<h3>Installation</h3>

<b>Theory:</b>
- http://disq.us/p/2f9zjx5
- https://stackoverflow.com/a/58595941/4695280

<b>Instructions for the recipe, created by me for this bundle:</b>
- https://flex.symfony.com/r/github.com/symfony/recipes-contrib/1099
<br>

<b>If instructions for bundle become anavailable by link above, here is the copy:</b>

<h6>How to test these changes in your application</h6>

<b>Step 0.</b> Allow installing "contrib" recipes in your application:

`composer config extra.symfony.allow-contrib true`

<b>Step 1.</b> Define the `SYMFONY_ENDPOINT` environment variable:

<pre>
# On *nix and Mac
export SYMFONY_ENDPOINT=https://flex.symfony.com/r/github.com/symfony/recipes-contrib/1099
# On Windows
SET SYMFONY_ENDPOINT=https://flex.symfony.com/r/github.com/symfony/recipes-contrib/1099
</pre>

<b>Step 2.</b> Install the package(s) related to this recipe:

`composer req "boolean-type/lorem-ipsum-bundle:^1.0"`

<b>Step 3.</b> Don't forget to unset the `SYMFONY_ENDPOINT` environment variable when done:
<pre>
# On *nix and Mac
unset SYMFONY_ENDPOINT
# On Windows
SET SYMFONY_ENDPOINT=
</pre>

Travis CI builds
----------------
https://travis-ci.com/github/BooleanType/symfony_knpu_lorem_ipsum_bundle/builds
