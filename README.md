Exceptional Bundle for Symfony2 [![Travis-CI Build Status](https://secure.travis-ci.org/nodrew/NodrewExceptionalBundle.png?branch=master)](http://travis-ci.org/#!/nodrew/NodrewExceptionalBundle)
======================================================================================================================================================================================



For use with the NodrewExceptionalBundle service at: http://www.getexceptional.com

## Installation Instructions

1. Download NodrewExceptionalBundle
2. Configure the Autoloader
3. Enable the Bundle
4. Add your Exceptional API key

### Step 1: Download NodrewExceptionalBundle

Ultimately, the NodrewExceptionalBundle files should be downloaded to the
`vendor/bundles/Nodrew/Bundle/ExceptionalBundle` directory.

This can be done in several ways, depending on your preference. The first
method is the standard Symfony2 method.

**Using the vendors script**

Add the following lines in your `deps` file:

```
[NodrewExceptionalBundle]
    git=http://github.com/nodrew/NodrewExceptionalBundle.git
    target=/bundles/Nodrew/Bundle/ExceptionalBundle

[exceptional-php]   
    git=https://github.com/ankane/exceptional-php.git
    target=/exceptional-php
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add http://github.com/nodrew/NodrewExceptionalBundle.git vendor/bundles/Nodrew/Bundle/ExceptionalBundle
$ git submodule add http://github.com/ankane/exceptional-php.git vendor/exceptional-php
$ git submodule update --init
```

### Step 2: Configure the Autoloader

``` php
<?php
// app/autoload.php

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    // ...
    'Nodrew'   => __DIR__.'/../vendor/bundles',
));


// Include the exceptional library.
require_once __DIR__.'/../vendor/exceptional-php/exceptional.php';
```

### Step 3: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Nodrew\Bundle\ExceptionalBundle\NodrewExceptionalBundle(),
    );
}
```

### Step 4: Add your Exceptional provider key

``` yaml
# app/config/config.yml
nodrew_exceptional:
    api_key:   [your api key]
```


## TODO

- More tests
