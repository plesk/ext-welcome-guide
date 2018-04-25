## Generic Welcome Extension
This is the internal repo for the new Welcome Extension



##Build the React Bundle

```
yarn build && cp dist/htdocs/bundle.js src/htdocs/bundle.js 
```


##Bundle the extension

Before bundling the extension you need to build the bundle file above

```
cd src/ && zip -9r ext-welcome.zip *
```