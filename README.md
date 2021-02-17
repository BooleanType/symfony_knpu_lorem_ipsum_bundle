https://symfonycasts.com/screencast/symfony-bundle

Usage
-----
  - in browser:
http://first-project/article/show
http://first-project/api/ipsum/
  - in console:
D:\server\www\LoremIpsumBundle>vendor\bin\simple-phpunit     # Run the tests.


How to make changes in bundle locally?
--------------------------------------
Changes, made in `vendor` folder, for bundle, installed from packagist (https://packagist.org/packages/boolean-type/lorem-ipsum-bundle) wan't work. So, I need to made them in local (ie this) bundle version.

<h5>Switch from packagist version to local:</h5>

1. `composer remove boolean-type/lorem-ipsum-bundle`

2. In <i>app's</i> `composer.json` add this:
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
