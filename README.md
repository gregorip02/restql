# RestQL

RestQL is a Laravel eloquent-based data resolution package. This library tries to
adopt GraphQL principles solving only the data that the client requests. RestQL uses
the eloquent models as an entry point to add queries to then.

<img src="./img/example.png" alt="Laravel RestQL"/>

## **Why?**

Imagine you have an application that manages authors, these authors can publish
articles and those articles can have comments from different authors. You have a
web client, for example, that uses axios to consume the data offered by your service.
Somewhere in your code, you need a list of the **author's names only**.

They wear something like that.

```js
axios.get('http://laravel.app/api/authors').then(({ data }) => {
  // Do something...
  console.log(data)
});
```

So, you have a route like this.

```php
// api.php
<?php

// Get all the authors using your typical laravel implementation.
Route::get('authors', function (Request $request, Author $author) {
  // Do something...
  return $authors->all();
});
```

Most likely you will use a controller and then use the author model and query the data.
Then you would have a response similar to this.

```js
[
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
  {...}
]
```

But what if you only need the name of the author? Imagine that your application
becomes huge and your user model handles a large number of attributes. This is where
data resolution packages come into play.

## **Install**

- Add the composer dependencie.

```bash
composer require gregorip02/restql
```

- Publish the package configuration.

```bash
php artisan vendor:publish
```

- Adds a list of model keys that point to the actual classes of the eloquent model.

```php
// config/restql.php
<?php
//...
  'allowed_models' => [
    'authors' => 'App\Author'
  ]
```

With this configuration, your Author model can now be an automatic data resolution model.

## **The Data Resolution Packages**

Data resolution packages are the way to optimize queries and responses based on
parameters received from the client. This is based on the REST fundamentals but tries
to add GraphQL principles. Fortunately, Laravel has a powerful ORM and makes
this implementation easy.

## **How it works**

Basically, RestQL filters the keys of the models received in the HTTP request and
compares them with the keys of the user configuration. These keys represent a
specific eloquent model.

The values of these keys are eloquent clauses (methods) accepted by RestQL and
the arguments of these clauses are sent as values.

For example, if a params like the following is sent in the request.

```json
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
// Assuming that the parent model we want to obtain is the author's data.
// The variable $query represents the query constructor of the parent model,
// in this example, the Author model.
$query->select(['name'])->with([
  'articles' => static function (Relation $relation) {
    $relation->select(['title']);
  }
]);
```

Let's see how to do it using the RestQL package.

## **Using**

Starting with the first version of RestQL, you can define a single endpoint.
For this example we will do it in the routes file `api.php`.

```php
// api.php
<?php

use Restql\Restql;

// The RestQL endpoint.
Route::get('restql', function (Request $request) {
  // This is not a facade.
  return Restql::resolve($request);
});
```

Now, you can re-factor your client's code so that it sends a parameter in the
request with the data it needs, in this case a list of author names. They wear
something like that.

The parameters of the query can be a json object that defines the clauses accepted
by RestQL, or you can encode this JSON in base64 if you want your URL to
appear "more secure".

```js
// This is an example using the request parameters directly.
axios.get('http://laravel.app/api/restql', {
  params: {
    authors: {
      select: 'name'
    }
  }
}).then(({ data }) => {
    // Do something...
    console.log(data)
});

// This is an example using the base64 encoded request parameters.
const toBase64 = (string) => new Buffer.from(string).toString('base64');
axios.get('http://laravel.app/api/restql', {
  params: {
    query: toBase64(JSON.stringify({
      authors: {
        select: 'name'
      }
    }))
  }
}).then(({ data }) => {
  // Do something...
  console.log(data)
});
```

Instead of having a long JSON response with unnecessary data, you would get
something like this. Likewise, this will considerably optimize your queries to
the database management system. In this case, it will run just a
`select id, name from authors` for example.


```js
{
  "authors": [
    { "id": 1, "name": "Kasey Yost" },
    { "id": 2, "name": "Ike Barton" },
    { "id": 3, "name": "Emie Daniel" },
    {...}
  ]
}
```

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
