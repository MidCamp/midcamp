# The Hatter

## Getting Started
 
 1. From inside the project root, run: `composer install`
 1. Run `npm install`
 1. `npm run butler` and allow the script to keep running and watching for changes. 
 1. For the styleguide site, visit [localhost:4000](http://localhost:4000/) in your browser of choice.

 ## Working on the styleguide
 Butler uses spress, a static site generator. This means that the /src folder compiles into the /build folder. All changes should happen in the src folder.

 Sass changes should occur in the /sass directory. These will automatically compile into a styles.css via the styles.scss file.

 Markup changes should happen either in the /includes folder or in the /content/templates folder. The includes folder contains template partials or components, and the files in the templates folder brings together those includes with layout markup to create templates.

 ### Making new components
 When creating a new component: 
 * create the component markup as a twig file in the /includes folder.
 * Create a new file in /content/components and copy one of the existing files in there. Then replace the include with your newly created include and change the title and description in the yaml of the file to match for your component. 
 * In the sass/components/ directory create your new sass partial with an underscore before the title (follow convention of other components in that directory).
 * Add your sass partial to the components section of the styles.scss file which lives in the sass/ directory. The components section starts on line 30 of that file.
 * Butler will be watching your changes and now you should be able to see your component appear in the components list. 

 ## The Toolchain
 
 The Front-end toolchain is set up the Butler:
 [Butler documentation] (https://github.com/palantirnet/butler) 
 
 Butler uses the following:
 * [Gulp] (http://gulpjs.com/)
 * [Spress] (http://spress.yosymfony.com/)
 * [Sass] (http://sass-lang.com/)
 
