@if(config('app.url') == \App\Utils\Fpos::DEMO_URL)
<meta name="description" content="{{ isset($meta['description']) ? $meta['description'] : 'FlexiblePos is an app where everyone can manage their business easily. Add products, inventory, create invoice/bill for sale/receivings, send invoice/bill to their vendors, can see accounts and reports.' }}" />
@isset($meta['keywords'])
<meta name="keywords" content="{{ $meta['keywords'] }}" />
@endisset
<meta name="author" content="{{ isset($meta['author']) ? $meta['author'] : 'FlexiblePos' }}" />
<meta name="copyright" content="{{ isset($meta['copyright']) ? $meta['copyright'] : 'FlexiblePos' }}" />
<meta name="application-name" content="{{ isset($meta['application-name']) ? $meta['application-name'] : 'FlexiblePos' }}" />
@isset($meta['noindex'])
<meta name=“robots” content=“noindex”>
@endisset

<meta property="og:title" content="{{ isset($meta['title']) ? $meta['title'] : 'FlexiblePos - Manager, Sell, and Profit.' }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ isset($meta['url']) ? $meta['url'] : url()->current() }}" />
@isset($meta['image'])
<meta property="og:image" content="{{ $meta['image'] }}" />
@endisset
<meta property="og:description" content="{{ isset($meta['description']) ? $meta['description'] : 'FlexiblePos is an app where everyone can manage their business easily. Add products, inventory, create invoice/bill for sale/receivings, send invoice/bill to their vendors, can see accounts and reports.' }}" />

<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ isset($meta['title']) ? $meta['title'] : 'FlexiblePos - Manager, Sell, and Profit.' }}" />
<meta name="twitter:description" content="{{ isset($meta['description']) ? $meta['description'] : 'FlexiblePos is an app where everyone can manage their business easily. Add products, inventory, create invoice/bill for sale/receivings, send invoice/bill to their vendors, can see accounts and reports.' }}" />
@isset($meta['image'])
<meta name="twitter:image" content="{{ $meta['image'] }}" />
@endif
@isset($meta['referralCode'])
<meta name="referral-code" content="{{ $meta['referralCode'] }}" />
@endisset

<link rel="canonical" href="{{ $meta['canonical'] ?? Request::url() }}">
@endif
