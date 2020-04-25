# **Select**

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
// List of attributes
$query->select(["id", "name", "email", "age"]);

// String as attribute, for only one field
$query->select(["id", "name"]);
```

> The select clausule forget the model hidden attributes for the query by default.

# **Where**

Add a basic where clause to the query.

##### Json Format
```javascript
{
    // Using explicit attributes
    "where": {
        "column": "id",
        "operator": "=",
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
// Using explicit attributes
$query->where("id", "=", 100);

// Using implicit attributes
$query->where("id", 100);

// Using super implicit attributes, this filter by the primary key
$query->where($model->getKeyName(), 100);
```
