<p align="center">
<a href="https://github.com/s-damian/damian-pagination-php">
<img src="https://raw.githubusercontent.com/s-damian/medias/main/package-logos/damian-pagination-php.png" width="400">
</a>
</p>

# PHP Pagination Library - Open Source

[![Tests](https://github.com/s-damian/damian-pagination-php/actions/workflows/tests.yml/badge.svg)](https://github.com/s-damian/damian-pagination-php/actions/workflows/tests.yml)
[![Static analysis](https://github.com/s-damian/damian-pagination-php/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/s-damian/damian-pagination-php/actions/workflows/static-analysis.yml)
[![Latest Stable Version](https://poser.pugx.org/s-damian/damian-pagination-php/v/stable)](https://packagist.org/packages/s-damian/damian-pagination-php)
[![License](https://poser.pugx.org/s-damian/damian-pagination-php/license)](https://packagist.org/packages/s-damian/damian-pagination-php)

## Damian Pagination PHP - Open Source Pagination

### Introduction - Damian Pagination PHP package

Damian Pagination PHP is an Open Source PHP library that allows you to generate simple and complete **pagination**.

This pagination is compatible for all projects in PHP (with MVC, without MVC, etc.).

This pagination is compatible with **Bootstrap 5** CSS.

> Paginate easily without limit!
```php
<?php

$pagination = new Pagination();

$pagination->paginate($countElements);

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

echo $pagination->render();
echo $pagination->perPageForm();
```

### Author

This package is developed by [Stephen Damian](https://github.com/s-damian)

### Requirements

* PHP 8.0 || 8.1 || 8.2 || 8.3


## Summary

* [Installation](#installation)
* [Installation](#installation)
* [Pagination instance methods](#pagination-instance-methods)
* [Examples](#examples)
* [Custom config](#custom-config)
* [Support](#support)
* [License](#license)


## Introduction

This Open Source pagination contains PHP files, and one CSS style sheet.

An example of a CSS style sheet is in ```vendor/s-damian/damian-pagination-php/src/css``` directory. You can edit them according to your needs.

This pagination also allows you to generate a **per page**. This will generate a form HTML tag with a select HTML tag and clickable options.


## Installation

### Installation with Composer

```
composer require s-damian/damian-pagination-php
```

### Installation without Composer

If you do not use Composer to install this package, you will have to manually "require" before using this package. Example:

```php
<?php

require_once './your-path/damian-pagination-php/src/DamianPaginationPhp/bootstrap/load.php';
```


## Pagination Instance Methods

| Return type    | Method                           | Description                                                               |
| -------------- | -------------------------------- | ------------------------------------------------------------------------- |
| void           | __construct(array $options = []) | Constructor.                                                              |
| void           | paginate(int $count)             | Activate the pagination.                                                  |
| null or int    | getLimit()                       | LIMIT: Number of items to retrieve.                                       |
| null or int    | getOffset()                      | OFFSET: From where start the LIMIT.                                       |
| int            | getCount()                       | Determine the total number of matching items in the data store.           |
| int            | getCountOnCurrentPage()          | Get the number of items for the current page.                             |
| int            | getFrom()                        | Get the result number of the first item in the results.                   |
| int            | getTo()                          | Get the result number of the last item in the results.                    |
| int            | getCurrentPage()                 | Get the current page number.                                              |
| int            | getNbPages()                     | Get the page number of the last available page (number of pages).         |
| int            | getPerPage()                     | The number of items to be shown per page.                                 |
| bool           | hasPages()                       | Determine if there are enough items to split into multiple pages.         |
| bool           | hasMorePages()                   | Determine if there are more items in the data store.                      |
| bool           | isFirstPage()                    | Determine if the paginator is on the first page.                          |
| bool           | isLastPage()                     | Determine if the paginator is on the last page.                           |
| bool           | isPage(int $pageNb)              | Determine if the paginator is on a given page number.                     |
| null or string | getPreviousPageUrl()             | Get the URL for the previous page.                                        |
| null or string | getNextPageUrl()                 | Get the URL for the next page.                                            |
| string         | getFirstPageUrl()                | Get the URL for the first page.                                           |
| string         | getLastPageUrl()                 | Get the URL for the last page.                                            |
| string         | getUrl(int $pageNb)              | Get the URL for a given page number.                                      |
| string         | render()                         | Make the rendering of the pagination in HTML format.                      |
| string         | perPageForm(array $options = []) | Make the rendering of the per page in HTML format.                        |


## Examples

### Simple example

```php
<?php

$pagination = new Pagination();

$pagination->paginate($countElements);

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

// Here your SQL query with $limit and $offset

// Then your listing of elements with a loop

echo $pagination->render();
echo $pagination->perPageForm();
```

#### Example rendering of pagination with Bootstrap 5:

[![Laravel Man Pagination](https://raw.githubusercontent.com/s-damian/medias/main/packages/damian-pagination-php-bootstrap-5-example.webp)](https://github.com/s-damian/larasort)

### Example with SQL queries

```php
<?php

use DamianPaginationPhp\Pagination;

// Count articles in DB
function countArticles(): int
{
    $sql = "SELECT COUNT(*) AS nb FROM articles";
    $query = db()->query($sql);
    $result = $query->fetch();
    
    return $result->nb;
}

// Collect articles from DB
function findArticles($limit, $offset)
{
    $sql = "SELECT * FROM articles LIMIT ? OFFSET ?";
    $query = db()->prepare($sql);
    $query->bindValue(1, $limit, PDO::PARAM_INT);
    $query->bindValue(2, $offset, PDO::PARAM_INT);
    $query->execute();

    return $query;
}

// Creating an object Pagination
$pagination = new Pagination();

// Paginate
$pagination->paginate(countArticles());

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

$articles = findArticles($limit, $offset);

// Show elements one by one that are retrieved from the database
foreach ($articles as $article) {
    echo htmlspecialchars($article->title);
}

// Show the Pagination
echo $pagination->render();
// Show the per page
echo $pagination->perPageForm();
```
The function ```db()``` is a return of result of the database connection (PDO instance for example).

Depending on your needs, you can also use this library with your favorite ORM.

### Example with a list of files of a directory

```php
<?php

use DamianPaginationPhp\Pagination;

$scandir = scandir('your_path_upload');

$listFilesFromPath = [];
$count = 0;
foreach ($scandir as $f) {
    if ($f !== '.' && $f !== '..') {
        $listFilesFromPath[] = $f;
        $count++;
    }
}

// Creating an object Pagination
$pagination = new Pagination();

// Paginate
$pagination->paginate($count);

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

// Listing
$files = array_slice($listFilesFromPath, $offset, $limit);

// Show files one by one
foreach ($files as $file) {
    echo $file;
}

// Show the Pagination
echo $pagination->render();
// Show the per page
echo $pagination->perPageForm();
```


## Add argument(s) to the instance

```php
<?php

// To change number of Elements per page:
$pagination = new Pagination(['pp' => 50]);
// Is 15 by default

// To change number of links alongside the current page:
$pagination = new Pagination(['number_links' => 10]);
// Is 5 by default

// To change the choice to select potentially generate with perPageForm():
$pagination = new Pagination(['options_select' => [5, 10, 50, 100, 500, 'all']]);
// The value of 'options_select' must be a array.
// Only integers and 'all' are permitted.
// Options are [15, 30, 50, 100, 200, 300] by default.

// To change the CSS style of the pagination (to another CSS class as default):
$pagination = new Pagination(['css_class_p' => 'name-css-class-of-pagintion']);
// The CSS class name is by default "pagination".

// To change the CSS style of the pagination active (to another CSS class as default):
$pagination = new Pagination(['css_class_link_active' => 'name-css-class-of-pagintion']);
// The active CSS class name is by default "active".

// To change the CSS style of a per page (select) (to another id id as default):
$pagination = new Pagination(['css_id_pp' => 'name-css-id-of-per-page-form']);
// The CSS ID name is by default  "per-page-form".

// To use Bootstrap CSS:
$pagination = new Pagination(['css_class_p' => 'pagination']);
// The CSS class name is by default "block-pagination"
// We must put "pagination"
```


## Custom config

You can change the default language. It's english (en) by default.

Supported languages: "cn", "de", "ee", "en", "es", "fr", "it", "jp", "pt", "ru".

Set default language:

```php
<?php

use DamianPaginationPhp\Config\Config;

// Change the language to "fr" (it's "en" by default.
Config::set(["lang" => "fr"]);
```


## Support

### Bugs and security Vulnerabilities

If you discover a bug or a security vulnerability, please send a message to Stephen. Thank you.

All bugs and all security vulnerabilities will be promptly addressed.


## License

This project is an Open Source package under the MIT license. See the LICENSE file for details.
