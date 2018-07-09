# Plesk Welcome Guide

This is the internal repo for the new Plesk Welcome Guide.

## Setup instructions

To be able to build your own package, you must have installed in your development environment:

* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/en/)
* [Yarn](https://yarnpkg.com/en/)

### Run Composer

Run Composer to build the required autoload files

```
> composer install --no-dev
```

Use the _update_ command to get the latest version of the dependencies.

### Build the React Bundle

Add required packages by running the command (only required once!): 

```
> yarn add --dev webpack webpack-cli babel-core babel-loader babel-plugin-transform-react-jsx babel-preset-env
```

The configuration files are already pre-configured, thus you just need to run the following command to create the proper JavaScript file with the the React UI library included:

```
> yarn build
```

This command will update the file _main.js_ in _src/htdocs/js/_.

### Bundle the extension

Before bundling the extension you need to build the bundle file (see section above). If the JavaScript file was created properly, you may create the ready-to-install package with the command:

```
> cd src/ && zip -6r ext-welcome-guide.zip * && cd ..
```

## Placeholders in JSON configuration

It is possible to make JSON configuration more flexible by using placeholders in JSON strings. The syntax is **%%placeholder%%**.

### Static placeholders

Currently there is only one static placeholder **%%username%%**, which resolves to the current Plesk user name.

```
Hallo, %%username%%!" -> "Hallo, Max Mustermann!
```

### Dynamic placeholders

Dynamic placeholder consists of a name and at least one parameter. Parameters are separated by | (vertical pipe) character. The syntax for dynamic placeholders is: **%%name|param1|param2|paramX%%**

#### extname

Returns official name of an extension.

Parameters:
1. Extension ID (required)

```
%%extname|advisor%% -> Security Advisor
```

#### image

Displays an image.

Parameters:
1. URL (required)
2. Position (optional) [left|right]

```
%%image|https://www.plesk.com/wp-content/uploads/2017/05/elvis_plesky.png%%
->
<img src="https://www.plesk.com/wp-content/uploads/2017/05/elvis_plesky.png" />
```

#### format

Provides options for formatting text.

Parameters:
1. Format type (required) [bold|italic|underline]
2. Text to format (required)

```
This is %%format|bold|important%% -> This is <strong>important</strong>
```

## Actions

Actions are defined in separate `actions` block, calls some built-in task and is referenced by buttons.

```
"actions": {
    "action1": {
        "taskId": "install",
        "extensionId": "wp-toolkit"
    }
}
```

```
"buttons": [
    {
        "actionId": "action1"
    }
]
```

### Tasks

#### install

For installing or opening extension.

Parameters:
1. extensionId - Extension ID (required)
2. titleInstall - Title of button for installation process (optional)
3. titleOpen - Title of button for open process (optional)

#### extlink

For opening extension.

Parameters:
1. extensionId - Extension ID (required)
2. title - Button text, if not set then the official extension name will be used (optional)

#### link

For opening specific URL address.

Parameters:
1. title - Button text (required)
2. url - URL address (required)
3. newWindow - Open in new window (optional)

#### addDomain

Shortcut for adding a new domain name.

Parameters:
1. title  - Button text (optional)

## Command-line interface usage examples

Get a list of available presets

```
> plesk ext welcome --list
```

Show current configuration

```
> plesk ext welcome --show
```

Show preset configuration

```
> plesk ext welcome --show -preset wordpress
```

Update current configuration from preset

```
> plesk ext welcome --select -preset business
```

Update current configuration from URL

```
> plesk ext welcome --input -url http://example.com/config.json
```
