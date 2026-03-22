<p align="center">
<a href="https://github.com/s-damian/damian-pagination-php">
<img src="https://raw.githubusercontent.com/s-damian/medias/main/package-logos/damian-pagination-php.png" width="400">
</a>
</p>

# PHP Pagination Library - Open Source

[![Tests](https://github.com/s-damian/damian-pagination-php/actions/workflows/tests.yml/badge.svg)](https://github.com/s-damian/damian-pagination-php/actions/workflows/tests.yml)
[![Total Downloads](https://poser.pugx.org/s-damian/damian-pagination-php/downloads)](https://packagist.org/packages/s-damian/damian-pagination-php)
[![Latest Stable Version](https://poser.pugx.org/s-damian/damian-pagination-php/v/stable)](https://packagist.org/packages/s-damian/damian-pagination-php)
[![License](https://poser.pugx.org/s-damian/damian-pagination-php/license)](https://packagist.org/packages/s-damian/damian-pagination-php)

## Damian Pagination PHP - Open Source Pagination

### Introduction - Damian Pagination PHP

Damian Pagination PHP is an open-source PHP library designed to provide simple yet comprehensive pagination functionality.

This pagination library is compatible with all PHP projects (with or without MVC frameworks) and is also compatible with Bootstrap 5 CSS.

### Key Features

- Simple library for **pagination**.
- Customizable **per page** options.
- Support for multiple **languages**.
- Compatible with **Bootstrap 5**.

> Paginate easily without limits 🚀

### Basic Example

```php
<?php

$pagination = new \DamianPaginationPhp\Pagination();

$pagination->paginate($countElements);

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

echo $pagination->render();
echo $pagination->perPageForm();
```

### Author

This package is developed by [Stephen Damian](https://github.com/s-damian).

### Requirements

- PHP `8.0` || `8.1` || `8.2` || `8.3` || `8.4` || `8.5`


## Summary

- [Introduction](#introduction)
- [Installation](#installation)
- [Pagination Instance Methods](#pagination-instance-methods)
- [Examples](#examples)
  - [Simple Example](#simple-example)
  - [Example With SQL Queries](#example-with-sql-queries)
  - [Example With a List of Files of a Directory](#example-with-a-list-of-files-of-a-directory)
- [Instance Options](#instance-options)
- [Language Configuration](#language-configuration)
- [Support](#support)
- [License](#license)


## Introduction

This Open Source pagination contains PHP files, and one CSS style sheet.

An example of a CSS style sheet is in `vendor/s-damian/damian-pagination-php/src/css` directory. You can edit them according to your needs.

This pagination library also allows you to generate a **per page** form. This will generate an HTML `<form>` tag with a `<select>` element and clickable options.


## Installation

### With Composer

```
composer require s-damian/damian-pagination-php
```

### Without Composer

If you do not use Composer, you must manually require the necessary files before using this package. Example:

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
| null or int    | getOffset()                      | OFFSET: Starting point for the LIMIT.                                       |
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

### Simple Example

```php
<?php

use DamianPaginationPhp\Pagination;

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

[![Damian Pagination PHP Bootstrap 5 Example](https://raw.githubusercontent.com/s-damian/medias/main/packages/damian-pagination-php-bootstrap-5-example.webp)](https://github.com/s-damian/larasort)

### Example With SQL Queries

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

The `db()` function must be defined to return a database connection instance, such as PDO.

Depending on your needs, you can also use this library with your favorite ORM.

### Example With a List of Files of a Directory

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


## Instance Options

```php
<?php

use DamianPaginationPhp\Pagination;

// To change the number of elements displayed per page:
$pagination = new Pagination(['pp' => 50]);
// Is 15 by default

// To change number of links alongside the current page:
$pagination = new Pagination(['number_links' => 10]);
// Is 5 by default

// To change the choice to select potentially generate with perPageForm():
$pagination = new Pagination(['options_select' => [5, 10, 50, 100, 500, 'all']]);
// The value of 'options_select' must be an array.
// Only integers and 'all' are permitted.
// Options are [15, 30, 50, 100, 200, 300] by default.

// To change the CSS style of the pagination (to another CSS class as default):
$pagination = new Pagination(['css_class_p' => 'name-css-class-of-pagination']);
// The CSS class name is by default "pagination".

// To change the CSS style of the pagination active (to another CSS class as default):
$pagination = new Pagination(['css_class_link_active' => 'name-css-class-of-pagination']);
// The active CSS class name is by default "active".

// To change the CSS style of a per page (select) (to another id as default):
$pagination = new Pagination(['css_id_pp' => 'name-css-id-of-per-page-form']);
// The CSS ID name is by default "per-page-form".

// To use Bootstrap CSS:
$pagination = new Pagination(['css_class_p' => 'pagination']);
// The CSS class name is by default "block-pagination"
// We must put "pagination"
```


## Language Configuration

You can change the default language, which is English ('en') by default.

Supported languages: `cn`, `de`, `ee`, `en`, `es`, `fr`, `it`, `jp`, `pt`, `ru`.

Set default language:

```php
<?php

use DamianPaginationPhp\Config\Config;

// Change the language to French ('fr').
// Note: It's in English ('en') by default.
Config::set(["lang" => "fr"]);
```


## Support

If you discover a **bug** or a **security vulnerability**, please send a message to Stephen. Thank you.

All bugs and all security vulnerabilities will be promptly addressed.


## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for more details.
