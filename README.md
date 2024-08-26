# LupaSearch Magento 2 Plugin

## Introduction

The LupaSearch Magento 2 Plugin integrates LupaSearch's search functionality into your Magento 2 store, providing enhanced search capabilities that improve the user experience.

## Requirements

- **PHP**: >=7.4
- **Magento 2**: compatible with Magento 2.2.x - 2.4.x

## Installation

Install with composer

```
composer require lupasearch/magento2-lupasearch-plugin
```

Enable modules

```
php bin/magento module:enable LupaSearch_LupaSearchPluginCore LupaSearch_LupaSearchPlugin
```

Run install scripts

```
php bin/magento setup:upgrade
```

Run compile scripts

```
php bin/magento setup:di:compile
```

Change Indexer Mode to "On Schedule" (only works on this mode)

```shell
bin/magento indexer:set-mode schedule lupasearch_product lupasearch_category
```

Enable cache

```shell
bin/magento cache:enable lupasearch
```

Configurations:

```
Stores -> Configuration -> Catalog -> LupaSearch
```

Indexing

```
bin/magento index:reindex lupasearch_product
bin/magento index:reindex lupasearch_category
```

Run indexer

```shell
bin/magento indexer:reindex lupasearch_product
bin/magento indexer:reindex lupasearch_category
```

Run consumers

```
bin/magento queue:consumers:start lupasearch.all
```
