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
| `products`    | – (products listing page)             | `{ "page": "products" }`                                  |
| `blogs`       | – (blogs listing page)                | `{ "page": "blogs" }`                                     |
| `gallery`     | –                                     | `{ "page": "gallery" }`                                   |
| `videos`      | –                                     | `{ "page": "videos" }`                                    |
| `clients`     | –                                     | `{ "page": "clients" }`                                   |
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
    "page": "home",
    "site_name": "Sanjay Sales",
    "meta_title": "Welding & Cutting Equipment | Sanjay Sales",
    "meta_description": "Authorised dealer of welding machines, cutting equipment ...",
    "meta_keywords": "welding machine, plasma cutter",
    "canonical_url": "https://www.yoursite.com/",
    "robots": "index, follow",
    "og_title": "Welding & Cutting Equipment | Sanjay Sales",
    "og_description": "Authorised dealer of welding machines ...",
    "og_image": "https://your-domain.com/uploads/seo/abc.jpg",
    "og_type": "website"
  }
}
```

`og_type` is `website` for fixed pages, `product` for products and `article` for blogs.
Detail pages use the SEO fields saved on the record; when a field is empty it is generated automatically
(title from the name, description from short description / excerpt / content, image from the record's image).

## Errors
- 422 – missing/invalid field: `{ "message": "The slug field is required when page is blog.", "errors": { "slug": ["..."] } }`
- 404 – record not found or inactive.

## Next.js usage (App Router `generateMetadata`)
```js
export async function generateMetadata({ params }) {
  const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/seo`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify({ page: 'blog', slug: params.slug }),
  }).then(r => r.json());
  const s = res.data;
  return {
    title: s.meta_title,
    description: s.meta_description,
    keywords: s.meta_keywords,
    robots: s.robots,
    alternates: s.canonical_url ? { canonical: s.canonical_url } : undefined,
    openGraph: { title: s.og_title, description: s.og_description, images: s.og_image ? [s.og_image] : [], type: s.og_type, siteName: s.site_name },
  };
}
```
