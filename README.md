# RestQL

[![Actions Status](https://github.com/gregorip02/restql/workflows/tests/badge.svg)](https://github.com/gregorip02/restql/actions)

RestQL is a Laravel eloquent-based data resolution package. This package tries to
adopt **GraphQL** principles solving only the data that the client requests.
RestQL uses your **Laravel** models as an entry point to add queries to then
based in the eloquent methods.

<img src="./docs/example.png" alt="Laravel RestQL"/>

## **The Data Resolution Packages**

Data resolution packages are the way to optimize queries and responses based on
parameters received from the client. This is based on the REST fundamentals but tries
to add GraphQL principles.

## **Why?**

Imagine you have an application that manages authors, these authors can publish
articles and those articles can have comments from different authors. You have a
web client, for example, that uses axios to consume the data offered by your service.
Somewhere in your code, you need a list of the **author's names only**.

They wear something like that.

```js
axios.get('http://api.laravel.app/api/authors').then(({ data: authors }) => {
  // Do something...
  console.log(authors)
});
```

So, you have a route like this.

```php
<?php
// api.php

use App\Author;
use Illuminate\Http\Request;

Route::get('authors', function (Request $request) {
  // Do something...
  $authors = Author::take(25)->get();

  return ['data' => compact('authors')];
});
```

Most likely you will use a controller, then use the author model and query the data.
Finally you would have a response similar to this.

```javascript
{
    "data": {
        "authors": [
            {
                "id": 1,
                "name": "Lazaro Kohler",
                "email": "greenfelder.jenifer@example.org",
                "email_verified_at": "2020-03-19T18:11:36.000000Z",
                "created_at": "2020-03-19T18:11:36.000000Z",
                "updated_at": "2020-03-19T18:11:36.000000Z"
              },
              {
                "id": 2,
                "name": "Miss Anastasia Klocko DVM",
                "email": "lemke.trinity@example.org",
                "email_verified_at": "2020-03-19T18:11:36.000000Z",
                "created_at": "2020-03-19T18:11:36.000000Z",
                "updated_at": "2020-03-19T18:11:36.000000Z"
              },
              { /* 23 more authors */ }
        ]
    }
}
```

But what if you only need a **author's names** collection for example? Imagine
that your application becomes huge and your user model handles a large number of
attributes.

## **Get started**

Install RestQL using composer.

```bash
composer require gregorip02/restql
```

Publish the package configuration.

```bash
php artisan restql:schema
```

Add the `RestqlAttributes` trait to your eloquent models.

```php
<?php

use Illuminate\Database\Eloquent\Model;
use Restql\Traits\RestqlAttributes;

class Article extends Model
{
    use RestqlAttributes;

    // ...
}
```

Set your schema definition.

> Since version 2.x of this package the configuration has been updated to increase
flexibility and internal behavior modification.

You must define your entire schema in the config file, RestQL will then interpret
it and execute the queries based on this file. With this there is a possibility
that you can remove internal functions, modify them or even create your own
implementations.

```php
<?php
// config/restql.php

return [
    /*
    |--------------------------------------------------------------------------
    | Data resolution schema
    |--------------------------------------------------------------------------
    |
    | Define a list of the models that RestQL can manipulate, create
    | authorizers and middlewares to protect your schema definition
    | resources.
    |
    | See https://github.com/gregorip02/restql/tree/stable/docs/Schema.md
    */

    'schema' => [
        'authors' => [
           'class'  => 'App\Author',
           'authorizer' => 'App\Restql\Authorizers\AuthorAuthorizer',
           'middlewares' => []
        ]
    ]
];
```

> **Specify the return type of your relationships.**

The developer must specify the return type of the relationships defined in the
eloquent model. This means that if your model has a `hasMany` type relationship
like the following it will not work.

```php
<?php

namespace App;

use App\Article;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * Get the author articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class); // This doesn't work
    }
}
```

Instead, you should set the return type of your associative methods (relationships).
Obtaining a code like the following. See also [Returning values](https://www.php.net/manual/en/functions.returning-values.php).

```php
<?php

namespace App;

use App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    /**
     * Get the author articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
```

Configure your endpoint.

```php
<?php
// api.php

use Restql\Restql;
use Illuminate\Http\Request;

Route::any('restql', function (Request $request) {
  return Restql::resolve($request);
});
```

## **Refactor time**

Now, you can re-factor your client's code so that it sends a parameter in the
request with the data it needs, in this case a collection of author names. They wear
something like that.

```js
// This is an example using the request parameters directly.
axios.get('http://api.laravel.app/api/restql', {
  params: {
    authors: {
        take: 25,
        select: 'name'
    }
  }
}).then(({ data: authors }) => {
    // Do something...
    console.log(authors)
});
```

The parameters of the query can be a json object that defines the clauses accepted
by RestQL, or **you can encode this JSON in base64** if you want your URL to
appear "more secure".

```js
// This is an example using the base64 encoded request parameters.
const toBase64 = (string) => new Buffer.from(string).toString('base64');

axios.get('http://api.laravel.app/api/restql', {
  params: {
    query: toBase64(JSON.stringify({
      authors: {
        take: 25,
        select: 'name'
      }
    }))
  }
}).then(({ data: authors }) => {
  // Do something...
  console.log(authors)
});
```

Instead of having a long JSON response with unnecessary data, you would get
something like this.

```javascript
{
  "data": {
    "authors": [
        { "id": 1, "name": "Kasey Yost" },
        { "id": 2, "name": "Ike Barton" },
        { "id": 3, "name": "Emie Daniel" },
        { /* 22 more author's names */ }
      ]
  }
}
```

Likewise, this will considerably optimize your queries to the database management
system. In this case, it will run just a `select id, name from authors` for example.

## **How it works**

Basically, RestQL filters the keys of the models received in the HTTP request and
compares them with the keys of the user configuration. These keys represent a
specific eloquent model. The values of these keys are eloquent clauses (methods)
accepted by RestQL and the arguments of these clauses are sent as values.

For example, if a params like the following is sent in the request.

```javascript
{
  "authors": {
    "select": "name",
    "with": {
      "articles": {
        "select": "title"
      }
    }
  }
}
```

RestQL will interpret this as the following.

```php
<?php

// Assuming that the parent model we want to obtain is the author's data.
// The variable $query represents the query constructor of the parent model,
// in this example, the Author model.
$query->select(['name'])->with([
  'articles' => static function (Relation $relation) {
    $relation->select(['title']);
  }
]);
```

You can read more about the RestQL Clausules <a href="./docs/Clausules.md"
                                                title="RestQL Documentation">here</a>.

# **What's next?**

Are you interested on contrib to this project? see the <a href="./NEXT.md"
                                                         title="Next features">NEXT.md</a> file.

# **Please support it**

This is a personal project that can be very useful, if you believe it, help me
develop new functionalities and create a pull request, I will be happy to review
and add it. So, You can also contribute to the team by buying a coffee.

<a href="https://www.buymeacoffee.com/BgHiZ9b" target="_blank">
    <img src="https://cdn.buymeacoffee.com/buttons/default-red.png"
        style="border-radius: 5px;"
        alt="Buy Us A Coffee"
        width="300"
        height="80"/>
</a>
