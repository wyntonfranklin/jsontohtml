# jsontohtml
A php class to convert json objects to html.



### Usage

Include or require the file. Ensure The HtmlElement class is in the same directory.

```php
include("JsonToHtml.php");
```



### Creating HTML from a JSON file.

Create a JsonToHtml object and read in a JSON file. Pass the file name to the function.

```php
$jthml = new JsonToHtml();
$htmlOutput = $jthml->readFile("data.json");
echo $htmlOutput;
```



### Write HTML To File.

You can pass the name of your html file.

```php
$jthml = new JsonToHtml();
$jthml->readFile("data.json");
$jthml->writeToFile($filename);
```



### Public Methods

| Method                  | Description                                        |
| ----------------------- | -------------------------------------------------- |
| readFile("filename")    | Read a file and attempts to convert it to html.    |
| writeToFile("filename") | Write output of readfile function to a given file. |
| getOutput()             | Gets the html output of the conversion.            |



### The HTMLElement

The HTMLElement class  stores the html element attributes and features prior to generation. You could use this to create html tags.



### Create HTMLElement

```php
$element = new HtmlElement();
$element->create("h1");
$element->setId("my-element");
$element->addGeneralAttributes("data-file","file.json");
$element->addContent("Hello World");
echo $element->getHtml(); // <h1 id="my-element" data-file="file.json">Hello World</h1>
```



### Create Psudo Child Elements

The HtmlElement has true child elements that is used to recursive produce html. However this can be mimic for simplicity.

```php
// Create first html element
$element = new HtmlElement();
$element->create("div");

// Create second html element

$element2 = new HtmlElement();
$element2->create("p");
$element2->addContent("This is a hello world");
$element->addContent($element2->createHtml());
echo $element->getHtml(); // <div><p>This is a hello world</p></div> 
```



### Create Children Elements

You can create child elements and add them to parent elements. You can then loop through all the child elements and them to your parent element.

```php
// create div
$element = new HtmlElement();
$element->create("div");

// create h1 element using constructor
$element2 = new HtmlElemenet("h1");
$element2->addContent("This is a header");

// create p element
$element3 = new HtmlElement();
$element3->create("p");
$element3->addContent("This is a hello world");

// add children to div
$element->addChild($element2);
$element->addChild($element3);

// Create Parent Div
foreach($element->getChildren() as $child){
    $element->addContent($child->getHtml()); // add child html to parent
}

echo $element->getHtml(); // get div with child elements html
```



### JSON Object File

Using this json parser you can create duplication objects which makes it easy to create a html document from you JSON file.

#### Creating a simple div with styling and list element.

```json
{
  "html": {
  	"div": {
        "h3": "List elements",
        "inline": "padding:5px;",
        "ul": {
          "li": "First List",
          "li": "Second list"
        },
   	}     
  }
}
```



#### Create a form using JSONToHTML

```json
{
  "html": {
      "div": {
        "h3": "Form Elements Section",
        "p": "This is my first form",
        "form": {
          "inline": "border: 1px solid #ccc; padding: 5px;",
          "label": {
            "text": "A Input",
            "inline": "display:block; margin-right:3px;"
          },
          "input": {
            "inline": "display:block",
            "type": "text",
            "placeholder": "My first input",
            "value": ""
          },
          "select": {
            "inline": "display:block",
            "option": "First Item",
            "option": "Second Item"
          },
          "textarea": {
            "text": "This is a a text area",
            "cols": "25",
            "inline": "display:block"
          }
        }
      },   
  }
}
```



### Supported Elements

A list of tested html elements. All elements should work. Some are special like <br> and <hr> and require special formating.

- html
- h1 - h6 
- head [ title, meta, link, style ]
- body, footer, header
- p, span
- br, hr - special