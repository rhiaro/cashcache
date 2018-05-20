# Cash Cache

I was going to call this `currencydb` but then I realised, what the hell was I thinking?

Historical exchange rate APIs are a pain and change all the time, have monthly limits and restrict the base currency. Ugh, how selfish. I made this to cache only the exchange rates I need on certain days, so I can unify all my transactions into one currency (or 3) without hammering the APIs, or having my main site code break all the freaking time.

## APIs

So far it supports CurrencyLayer and Fixer.io (last API changes were because CurrencyLayer bought them).

* CurrencyLayer has 168 currencies, 1000 reqs/mo, and the base currency is USD.
* Fixer.io has 170 currencies, 1000 reqs/mo, and the base currency is EUR.

I'll just alternate if I end up needing more than 1000 reqs/mo.

## Do wut?

Historical exchange rates for specified currencies (comma-separated string, or array, or 3 char codes) are loaded for between two chosen dates (`Y-m-d`). They are dumped as JSON, to one file per day. The file is named after the date. The JSON is like:

```
{
    "date": "2013-03-16",
    "EUR": {
        "USD": "1.307716",
        "AUD": "1.256333",
        "CAD": "1.333812",
        "PLN": "4.150819",
        "MXN": "16.259128"
    }
}
```

APIs are not hit again if data for a currency on a date is already stored. (TODO: add a 'refetch' flag.)