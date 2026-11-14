# SEO API

One common endpoint for every page of the website.

`POST https://your-domain.com/api/seo`
Headers: `Content-Type: application/json`, `Accept: application/json`

## Request body

| page          | extra fields                          | example                                                   |
|---------------|---------------------------------------|-----------------------------------------------------------|
| `home`        | –                                     | `{ "page": "home" }`                                      |
| `about`       | –                                     | `{ "page": "about" }`                                     |
| `contact`     | –                                     | `{ "page": "contact" }`                                   |
| `blogs`       | – (blogs listing page)                | `{ "page": "blogs" }`                                     |
| `gallery`     | –                                     | `{ "page": "gallery" }`                                   |
| `videos`      | –                                     | `{ "page": "videos" }`                                    |
| `download`    | – (catalogues page)                   | `{ "page": "download" }`                                  |
| `category`    | `slug`                                | `{ "page": "category", "slug": "welding-machines" }`      |
| `subcategory` | `category` (slug), `slug`             | `{ "page": "subcategory", "category": "welding-machines", "slug": "arc-welders" }` |
| `product`     | `slug`                                | `{ "page": "product", "slug": "mig-350" }`                |
| `blog`        | `slug`                                | `{ "page": "blog", "slug": "welding-tips" }`              |

## Response (same shape for every page)

```json
{
  "success": true,
  "message": "OK",
  "data": {
    "page": "blogs",
    "page_name": "Blog",
    "site_name": "Sanjay Sales",
    "meta_title": "Blog || Welding & Cutting Equipment",
    "meta_keywords": "welding machine, plasma cutter",
    "meta_description": "Authorised dealer of welding machines, cutting equipment ...",
    "head": "<link rel=\"canonical\" href=\"https://www.yoursite.com/blogs\">\n<meta property=\"og:type\" content=\"website\">",
    "body": "<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-XXXX\"></iframe></noscript>"
  }
}
```

Fields match **Admin › SEO Setup › Edit Page SEO**: Page Name, Meta Title, Meta Keyword, Meta Description, Head, Body.

- `meta_description` is returned as plain text (the admin editor's formatting is stripped) so it can go straight into `<meta name="description">`.
- `head` – raw HTML (canonical, OG tags, Google Analytics ...) to inject inside `<head>`.
- `body` – raw HTML (e.g. GTM noscript) to inject at the start of `<body>`.
- Detail pages (`category`, `subcategory`, `product`, `blog`) use the SEO fields saved on the record; empty
  meta fields are generated automatically.
- `head` / `body` for `category`, `subcategory`, `product` and `blog` come from the Head / Body fields in the
  admin Add/Edit form of that record (`null` when empty).
- `products` (product listing) and `clients` no longer have SEO and now return **422**.

## Errors
- 422 – missing/invalid field: `{ "message": "The slug field is required when page is blog.", "errors": { "slug": ["..."] } }`
- 404 – record not found or inactive.

## Next.js usage (App Router)
```js
async function getSeo(body) {
  const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/seo`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify(body),
  }).then(r => r.json());
  return res.data;
}

export async function generateMetadata() {
  const s = await getSeo({ page: 'blogs' });
  return { title: s.meta_title, description: s.meta_description, keywords: s.meta_keywords };
}

// head / body are raw HTML snippets – render them in the page/layout, e.g.
// <head dangerouslySetInnerHTML={{ __html: s.head ?? '' }} />  (or parse into <Script>/<meta> tags)
// <div dangerouslySetInnerHTML={{ __html: s.body ?? '' }} />   at the top of <body>
```
