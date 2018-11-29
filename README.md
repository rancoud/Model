# Model Package

[![Build Status](https://travis-ci.org/rancoud/Model.svg?branch=master)](https://travis-ci.org/rancoud/Model) [![Coverage Status](https://coveralls.io/repos/github/rancoud/Model/badge.svg?branch=master)](https://coveralls.io/github/rancoud/Model?branch=master)

Abstract Model for better data manipulation between code and database.  

## Dependencies
[Database package](https://github.com/rancoud/Database)

## Installation
```php
composer require rancoud/model
```

## How to use it?
Extends Rancoud\Model\Model to your class.  
You have to implement two abstract methods setFields and setTable.  
* setFields is for setting the fields in table  
* setTable is for setting the table in database  
```php
class User extends Model
{
    protected function setFields(): void
    {
        $this->fields = [
            'id'       => new Field('int', ['not_null', 'unsigned', 'pk']),
            'nickname' => new Field('varchar', ['max:255', 'not_null'])
        ];
    }

    protected function setTable(): void
    {
        $this->table = 'user';
    }
}
```

Now you have methods for pagination, create, read, update and delete.  

```php
// $database is an instance of Rancoud\Database\Database
$user = new User($database);

$newId = $user->create(['nickname' => 'rancoud']);

$row = $user->one($newId);
// you will have an array representing data in database with correct types
// here : ['id' => 1, 'nickname' => 'rancoud'];
$rows = $user->all();
// here it's all rows in table : [ ['id' => 1, 'nickname' => 'rancoud'] ] 

$user->update(['nickname' => 'rancoud2'], $newId);

$user->delete($newId);
```

Model::all() accept an array with some keys that triggers specific actions  

```php
// $database is an instance of Rancoud\Database\Database
$user = new User($database);

// 50 rows using LIMIT 50,50
$rows = $user->all(['page' => 1]);
// 10 rows using LIMIT 10,10
$rows = $user->all(['count' => 10, 'page' => 1]);

// count rows in table
$count = $user->all(['rows_count' => 1]);

// return all rows with no limit 
$count = $user->all(['no_limit' => 1]);

// change order by
$count = $user->all(['order' => 'nickname']);
// change order by and order
$count = $user->all(['order' => 'nickname|desc']);
// multiple change order by and order
$count = $user->all(['order' => 'nickname|desc,id|asc']);
```

You can change values output in Model::all() with override functions:  
* getSqlAllSelectAndFillSqlParams(params: array)  
* getSqlAllJoinAndFillSqlParams(params: array)  
* getSqlAllWhereAndFillSqlParams(params: array)  

## Callbacks
You can add callback before and after create, update and delete.  
```php
ImplementModel::addBeforeCreate('a', function($sql, $params){
    // for modifying sql and params use this return otherwise don't
    return [$sql, $params];
});

ImplementModel::addAfterCreate('a', function($newId, $params){
    // for modifying params use this return otherwise don't
    return $params;
});

ImplementModel::addBeforeUpdate('a', function($sql, $params){
    // for modifying sql and params use this return otherwise don't
    return [$sql, $params];
});

ImplementModel::addAfterUpdate('a', function($params){
    // for modifying params use this return otherwise don't
    return $params;
});

ImplementModel::addBeforeDelete('a', function($sql, $params){
    // for modifying sql and params use this return otherwise don't
    return [$sql, $params];
});

ImplementModel::addAfterDelete('a', function($params){
    // for modifying params use this return otherwise don't
    return $params;
});
```

You can use JsonOutput trait for adding json format for the model.  

## Field Constructor
### Settings
#### Mandatory
| Parameter | Type | Description |
| --- | --- | --- |
| type | string | type of field, values used : int\|float\|char\|varchar\|text\|date\|datetime\|time\|timestamp\|year |

#### Optionnals
| Parameter | Type | Default value | Description |
| --- | --- | --- | --- |
| rules | array | [] | rules for checking values, values used : pk\|fk\|unsigned\|email\|not_null\|max\|min\|range\|Rancoud\Model\CustomRule |
| default | mixed | false | default value when none given |

## Field Methods
* isPrimaryKey(): bool  
* isForeignKey(): bool  
* isNotNull(): bool  
* getDefault(): mixed  
* formatValue(value: mixed): ?mixed  

## Model Constructor
### Settings
#### Mandatory
| Parameter | Type | Description |
| --- | --- | --- |
| $database | \Rancoud\Database\Database | Database Instance |

## Model Methods
### General Commands
* all(params: array, [validFields: array = []]): array|bool|int  
* one(id: mixed, [...ids: mixed = []]): array  
* create(args: array): bool|int  
* update(args: array, id: mixed, [...ids: mixed = []]): void  
* delete(id: mixed, [...ids: mixed = []]): void  
* getLastInsertId(): ?int  

### Database error
* getDatabaseErrors(): ?array  
* getDatabaseLastError(): ?array  

### Static Callbacks
* addBeforeCreate(name: string, callback: mixed): void  
* addAfterCreate(name: string, callback: mixed): void  
* addBeforeUpdate(name: string, callback: mixed): void  
* addAfterUpdate(name: string, callback: mixed): void  
* addBeforeCreate(name: string, callback: mixed): void  
* addAfterDelete(name: string, callback: mixed): void  

* removeBeforeCreate(name: string): void  
* removeAfterCreate(name: string): void  
* removeBeforeUpdate(name: string): void  
* removeAfterUpdate(name: string): void  
* removeBeforeDelete(name: string): void  
* removeAfterDelete(name: string): void  

## Static Helper Methods
* getCountPerPage(args: array): int  
* getLimitOffsetCount(args: array): array  
* getOrderByOrderField(args: array, [validFields: array = []]): array  
* getPageNumberForHuman(args: array): int  
* getPageNumberForSql(args: array): int  
* hasInvalidPrimaryKey(value: int): bool  
* hasLimit(args: array): bool  
* implodeOrder(orders: array): string  
* isRowsCount(args: array): bool  
* isValidFieldForOrderBy(field: string, [validFields: array = []]): bool  

## How to Dev
`./run_all_commands.sh` for php-cs-fixer and phpunit and coverage  
`./run_php_unit_coverage.sh` for phpunit and coverage  