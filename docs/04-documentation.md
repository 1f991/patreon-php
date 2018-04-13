1. [Getting Started](01-getting-started.md)
2. [Obtaining OAuth Tokens For Patrons](02-oauth.md)
3. [Patreon Integration Examples](03-examples.md)
4. Method Documentation
5. [Library Architecture](05-architecture.md)

# Resources

## Current User

Access using the `me()` method on `Patreon`, e.g: `$patreon->me()->get()`

* `get()` Returns a [`User`](#user). Depending on context, this will either be a
Campaign Creator (when accessed using a `Creators Access Token`) or a User who
*could* be a Patron of the OAuth Client Creator's Campaign.

## Campaigns

Access using the `campaigns()` method on `Patreon`, e.g:
`$patreon->campaigns()->getMyCampaign()`

* `getMyCampaign()` Returns the [`Campaign`](#campaign) belonging to the
Creator Access Token owner.
* `getMyCampaignWithPledges()` Returns the [`Campaign`](#campaign)
belonging to the Creator Access Token owner with the [`Pledges`](#pledges)
property populated with all of the Campaign's Pledges.
* `getCampaign(int $id)` Returns a [`Campaign`](#campaign) by ID.
* `getCampaignWithPledges(int $id)` Returns a [`Campaign`](#campaign) by
ID  with the [`Pledges`](#pledges) property populated with all of the Campaign's
Pledges.

## Pledges

Access using the `pledges()` method on `Patreon`, e.g: `$patreon->pledges()->getCampaignPledges($int $id)`

* `getCampaignPledges($int id)` Returns a
[`Collection`](/docs/05-architecture.md#collections) of [`Pledges`](#pledges).
* `getPageOfCampaignPledges($campaign, $count, $sort, $cursor)` Returns a
[`Collection`](/docs/05-architecture.md#collections) of [`Pledges`](#pledges)
for the requested page.

## Webhook

Access using the `webhook()` method on `Patreon`, e.g:
`$patreon->webhook()->accept(...)`

* `accept($body, $secret, $signature)` Verifies the request signature against
the body and returns a [`Collection`](/docs/05-architecture.md#collections) of [`Pledges`](#pledges), or throws a SignatureVerificationFailed Exception.

# Entities

## Address

* 7 Properties — see [Entities\Address](/src/Patreon/Entities/Address.php)

## Campaign

* 27 Properties — see [Entities\Campaign](/src/Patreon/Entities/Campaign.php)
* 1 Relation — `creator`
* 3 Collections — `goals`, `pledges` and `rewards`

## Card

* 8 Properties — see [Entities\Card](/src/Patreon/Entities/Card.php)

## Goal

* 6 Properties — see [Entities\Goal](/src/Patreon/Entities/Goal.php)
* 2 Helpers — `hasBeenCompleted()` and `isCompleted()`

## Pledge

* 9 Properties — see [Entities\Pledge](/src/Patreon/Entities/Pledge.php)
* 5 Relations — `address`, `card`, `creator`, `patron`, `reward`
* 4 Helpers — `isActive()`, `isPaymentDeclined()`, `hasMadeAPayment()`, `hasReward()`

## Reward

* 17 Properties — see [Entities\Reward](/src/Patreon/Entities/Reward.php)
* 1 Relation — `campaign`
* 3 Helpers — `isAvailableToChoose()`, `hasRemainingLimit()`, `isSystemReward()`

## User

* 23 Properties — see [Entities\User](/src/Patreon/Entities/User.php)
* 1 Relation — `campaign`
* 1 Collection — `pledges`
* 2 Helpers — `hasActivePledge()`, `isCreator()`
