# Generic Welcome Extension
This is the internal repo for the new Welcome Extension

## Build the React Bundle

```
yarn build && cp dist/htdocs/bundle.js src/htdocs/bundle.js
```

## Bundle the extension

Before bundling the extension you need to build the bundle file above

```
cd src/ && zip -9r ext-welcome.zip *
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
