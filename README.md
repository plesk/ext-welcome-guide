# Welcome Extension

This is the internal repo for the new Welcome Extension.

## Setup instructions

### Build the React Bundle

```
yarn build && cp dist/htdocs/bundle.js src/htdocs/bundle.js
```

### Bundle the extension

Before bundling the extension you need to build the bundle file above

```
cd src/ && zip -9r ext-welcome.zip *
```

## Placeholders in JSON configuration

It is possible to make JSON configuration dynamic by using placeholders in JSON strings. The syntax for all types of placeholders is **%%placeholder%%**.

### Predefined variables

Currently there is only one predefined variable **current-user-name**, which resolves to the current Plesk user name.

```
Hallo, %%current-user-name%%!" -> "Hallo, Max Mustermann!
```

### Actions

An action consists of a name and at least one parameter. Parameters are separated by | (vertical pipe) character. The syntax for action placeholder is: **%%action-name|param1|param2|paramX%%**

#### Install - Link to specific extension.

Depending on whether particular extension is installed or not, this action allows to install new or open existing extension.

Parameters:
1. Extension ID (required)
2. Message when extension is not installed yet (required)
3. Message when extension is already installed (required)

```
%%install|wp-toolkit|Install new WordPress website|Open your WordPress website%%
```

Depending on if you have the WordPress Toolkit installed, the output will be either:

```
<a href="ext-open-link">Open your WordPress website</a>
```

or:

```
<a href="ext-install-link">Install new WordPress website</a>
```

Is it *WordPress* or *Wordpress*? Is it *Joomla!* or *Joomla*? To make it easier by not having to remember correct names of extensions, the special variable *{{name}}* can be used inside of an *install* action. It resolves to the official name of an extension:

```
%%install|joomla-toolkit|Create a website using {{name}}|Configure your website with {{name}}%%
->
<a href="ext-install-link">Create a website using Joomla! Toolkit</a>
```

#### Extlink

Creates link to open specific extension using the official extension name.

Parameters:
1. Extension ID (required)

```
%%extlink|wp-toolkit%% -> <a href="ext-open-link">WordPress Toolkit</a>
```

#### Extname

Returns official extension name.

Parameters:
1. Extension ID (required)

```
%%extname|advisor%% -> Security Advisor
```

#### Link

Creates a hyperlink.

Parameters:
1. Link text (required)
2. URL (required)
3. Open in new window (required) [true|false]

```
%%link|Google|https://www.google.de|true%% -> <a href="https://www.google.de" target="_blank">Google</a>
```

#### Image

Displays an image.

Parameters:
1. URL (required)
2. Position (optional) [left|right]

```
%%image|https://www.plesk.com/wp-content/uploads/2017/05/elvis_plesky.png%%
->
<img src="https://www.plesk.com/wp-content/uploads/2017/05/elvis_plesky.png" />
```

#### Format

Provides options for formatting text.

Parameters:
1. Format type (required) [bold|italic|underline]
2. Text to format (required)

```
This is %%format|bold|important%% -> This is <strong>important</strong>
```

## Command-line interface usage examples

Get a list of available presets
> plesk ext welcome --list

Show current configuration
> plesk ext welcome --show

Show preset configuration
> plesk ext welcome --show -preset wordpress

Update current configuration from preset
> plesk ext welcome --select -preset business

Update current configuration from URL
> plesk ext welcome --input -url http://example.com/config.json
