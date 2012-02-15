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
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add http://github.com/nodrew/NodrewExceptionalBundle.git vendor/bundles/Nodrew/Bundle/ExceptionalBundle
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

## Optional Configuration

These options may be added to the configuration. 

``` yaml
// app/config/config.yml
nodrew_exceptional:
    use_ssl:  false
    context_id: ~
    blacklist:
        - password
        - ssn
```

### use_ssl

type: boolean

This command is used to turn on SSL processing. It is off by default, due to many systems not having the proper openssl libraries installed. However, if you do have it available and you are at all concerned that there may be sensitive data in these transmissions, I highly suggest enabling this.

### blacklist

type: array

This is perhaps the single most important piece for your user's security. This will allow you to add parameters that will be filtered out of the GET and POST data sent to Exceptional. Great for making sure password, ssn and credit card numbers don't end up in plain text, in your error logs for all of eternity. Please, for all of our sakes, use this.

Example: 

``` yaml
// app/config/config.yml
nodrew_exceptional:
    blacklist:
        - password
        - ssn
        - credit_card_number
```
In this case, the following will be transformed:

Original:

``` json
{'password':'secret', 'password2':'secret', 'ssn':'111-111-1111', 'credit_card_number': '1111111111111111', 'name':'joe', 'zip':'10001'} 
```

Will get turned into this:

``` json
{'password':'[PROTECTED]', 'password2':'[PROTECTED]', 'ssn':'[PROTECTED]', 'credit_card_number': '[PROTECTED]', 'name':'joe', 'zip':'10001'} 
```

If you notice, the field password2 was also matched, since it has the word password in it. This is true for all of these. Any keys that even contain the filtered key, will be filtered. 

### context_id

This is perhaps the most useful feature. Exceptional provides for a means of adding extra, contextual data to the error log. In this case, it we are doing so through a service handler, which will allow you the flexibility of loading your own data into any response that is generated. To do this, let's create a simple hander for adding the Symfony2 username and userId for logged in users.

#### Step 1: Create your context handler class.

``` php
<?php
// src/Acme/DemoBundle/Handler/ExceptionalContextHandler.php

namespace Acme\DemoBundle\Handler;

use Nodrew\Bundle\ExceptionalBundle\Handler\ContextHandlerInterface,
    Symfony\Component\Security\Core\SecurityContext;

class ExceptionalContextHandler implements ContextHandlerInterface
{
    protected $securityContext;
    
    /**
     * @param Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        $context = array();
        $token   = $this->securityContext->getToken();

        if ($token->isAuthenticated()) {
            $context['userId']   = $token->getUser()->getId();
            $context['username'] = $token->getUser()->getUsername();
        }

        return $context;
    }
}
```
#### Step 2: Create your service id

``` xml
// src/Acme/DemoBundle/Resources/config/services.xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <parameters>
        <parameter key="acme.exceptional.context.handler.class">Acme\DemoBundle\Handler\ExceptionalContextHandler</parameter>
    </parameters>

    <services>
        <service id="acme.exceptional.context.handler" class="%acme.exceptional.context.handler.class%">
            <argument type="service" id="security.context" />
        </service>
    </services>
</container>
```
#### Step 3: Add your context id to the config

``` yaml
// app/config/config.yml
nodrew_exceptional:
    context_id: acme.exceptional.context.handler
```

And that's it. Now you should see the username and userId added to the params in exceptional, if a user is logged in. If they are not, then it will be blank. You can of course add your own parameters to that array. Just be sure that it returns an array. If it returns anything else, then it will be skipped.


## TODO

- More tests
