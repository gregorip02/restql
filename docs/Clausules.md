# RestQL Clausules

<!-- TODO: Document this.. -->

## **Select**

Filter the data by the client needs only.

##### Json Format
```javascript
{
    // List of attributes
    "select": ["name", "email", "age"],

    // String as attribute, for only one field
    "select": "name"
}
```

> This clausule add the model primaryKey name using the getKeyName() method.
> In this example, we used the "id" as primary key.

##### RestQL Implementation
```php
<?php
// List of attributes
$query->select(["id", "name", "email", "age"]);

// String as attribute, for only one field
$query->select(["id", "name"]);
```

## **Take**

Set the "limit" value of the query.

##### Json Format
```javascript
{
    "take": 10
}
```

##### RestQL Implementation
```php
<?php
$query->take(10);
```

> **Important** The limit and take query builder methods may not be used when constraining eager loads.

## **Where**

Add a basic where clause to the query.

##### Json Format
```javascript
{
    // Using explicit attributes
    "where": {
        "column": "id", // Optional, default is "id"
        "operator": "=", // Optional, default is "="
        "value": 100
    },

    // Using implicit attributes
    "where": ["id", 100],

    // Using super implicit attributes, this filter by the primary key
    "where": 100
}
```

##### RestQL Implementation
```php
<?php
// Using explicit attributes
$query->where("id", "=", 100);

// Using implicit attributes
$query->where("id", 100);

// Using super implicit attributes, this filter by the primary key
$query->where($model->getKeyName(), 100);
```

## **Sort**

Sort the data by the column name.

##### Json Format
```javascript
{
    // Using explicit attributes
    "sort": {
        "column": "name",
        "direction": "desc" // Optional, default is "asc"
    },

    // Using implicit attributes
    "sort": ["name", "desc"],

    // Using super implicit attributes
    "sort": "name"
}
```

##### RestQL Implementation
```php
<?php
// Using explicit attributes
$query->orderBy("name", "desc");

// Using implicit attributes
$query->orderBy("name", "desc");

// Using super implicit attributes
$query->orderBy("name");
```

## **With**

The with clausule allow you to include model relationships. For example, if an model
called `Book` has a relationship called `Author`, you will can get these data in
one request.


##### Json Format
```javascript
{
    "books": {
        // Add yor clausules, then add more data.
        "select": "title",
        "with": {
            // The with objet wanna be a relationship list with more
            // RestQL Clausules.
            "author": {
                "select": "name",
                // In effect, here you will can add more "with" clausules..
                "with": {
                    // Author relathionships...
                }
            }
        }
    }
}
```

##### RestQL Implementation
```php
<?php
$query->select(['title'])->with([
    'author' => static function (Relation $relation) {
        $relation->select(['name']);
        $relation->with(/* More author relation here */);
    }
])
```
