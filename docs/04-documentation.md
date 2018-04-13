1. [Getting Started](01-getting-started.md)
2. [Obtaining OAuth Tokens For Patrons](02-oauth.md)
3. [Patreon Integration Examples](03-examples.md)
4. Method Documentation
5. [Library Architecture](05-architecture.md)

# Resources

## Current User

Access using the `me()` method on `Patreon`, e.g:

```php
$patreon->me();
```

* `get()` Returns a `User` Entity. Depending on context, this will either be a
Campaign Creator (when accessed using a `Creators Access Token`) or a User who
*could* be a Patron of the OAuth Client Creator's Campaign.

## Campaigns

* `getMyCampaign()` Returns the [`Campaign`](#campaign) Entity belonging to the
Creator Access Token owner.
* `getMyCampaignWithPledges()` Returns the [`Campaign`](#campaign) Entity
belonging to the Creator Access Token owner with the [`Pledges`](#pledges)
property populated with all of the Campaign's Pledges.
* `getCampaign(int $id)` Returns a [`Campaign`](#campaign) Entity by ID.
* `getCampaignWithPledges(int $id)` Returns a [`Campaign`](#campaign) Entity by
ID  with the [`Pledges`](#pledges) property populated with all of the Campaign's
Pledges.

## Pledges

## Webhook

# Entities

## Address

## Campaign

## Card

## Goal

## Pledge

## Reward

## User

# Exceptions
