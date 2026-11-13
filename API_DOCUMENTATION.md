# Sanjay Sales – Website API

This is the JSON API for the Next.js website at https://sanjay-sales-next.vercel.app.
The Laravel admin panel and the API run from the same project.

- **Base URL:** `https://<your-laravel-domain>/api` (local: `http://localhost:8000/api`)
- **Auth:** none. Every endpoint is public and returns only **active / published** records.
- **Headers:** send `Accept: application/json`. For `POST`, also send `Content-Type: application/json`. Form-data works too.
- **CORS:** open to all origins by default (Laravel's built-in `HandleCors`).
- **Rate limits:** read endpoints allow 300 requests per minute per IP. `POST /inquiries` allows 5 per minute per IP. Over the limit you get `429`.
- **Images and files:** always full URLs, e.g. `https://<domain>/uploads/products/abc.jpg`.

## Response format

Every response uses the same envelope.

```json
// success
{ "success": true, "message": "OK", "data": { }, "meta": { } }

// error
{ "success": false, "message": "First error message", "errors": { "field": ["..."] } }
```

`meta` only appears on paginated lists:

```json
"meta": { "current_page": 1, "per_page": 12, "total": 9, "last_page": 1, "from": 1, "to": 9 }
```

| HTTP | When |
|---|---|
| 200 | OK |
| 201 | Inquiry created |
| 404 | `{"success":false,"message":"Record not found."}`: wrong slug, or the record is inactive |
| 405 | Wrong method |
| 422 | Validation failed (`errors` lists every field) |
| 429 | `{"success":false,"message":"Too many requests. Please wait a minute and try again."}` |
| 500 | `{"success":false,"message":"Something went wrong. Please try again later."}` |

## Which page uses which endpoint

| Website page | Endpoint(s) |
|---|---|
| Every page (top bar, menu, footer, floating buttons) | `GET /settings` |
| Home `/` | `GET /home` |
| Our Products dropdown | `GET /categories` |
| Products `/products` | `GET /products`, plus `GET /categories` for the sub-category sidebar and its counts |
| Category or sub-category listing | `GET /products?category=…` or `GET /products?subcategory=…` |
| Product detail `/productdetail` | `GET /products/{slug}` |
| Gallery `/gallery` | `GET /gallery` (Photo tab) and `GET /videos` (Video tab) |
| Blog `/blog` | `GET /blogs?page=1` |
| Blog detail `/blogdetail` | `GET /blogs/{slug}` |
| Download Catalogue `/download` | `GET /catalogues` |
| Contact `/contact` (Inquire Now form) | `GET /settings` and `POST /inquiries` |
| About `/about` | `GET /home` (`about`, `stats`, `clients`) |

---

## 1. GET `/settings`

Returns contact details, social links and the category menu. Contact details come from `config/site.php`, where each value can be overridden in `.env`.

**Request:** `GET /api/settings`

**Response 200**
```json
{
  "success": true,
  "message": "OK",
  "data": {
    "name": "Sanjay Sales",
    "tagline": "Trusted supplier of welding, cutting and industrial solutions.",
    "contact": {
      "phones": ["+91 98250 47028", "+91 93749 91103"],
      "emails": ["san_shah120@yahoo.com"],
      "whatsapp": "919825047028",
      "address": "10, Mangal Estate, Nr. Chakudia Mahadev Temple, Rakhial, Ahmedabad - 380023",
      "map_embed_url": "https://www.google.com/maps?q=Sanjay+Sales+Rakhial+Ahmedabad&output=embed",
      "map_link": "https://maps.google.com/?q=Sanjay+Sales+Rakhial+Ahmedabad",
      "business_hours": "Mon – Sat: 10:00 AM – 7:00 PM",
      "whatsapp_link": "https://wa.me/919825047028"
    },
    "social": { "facebook": "https://facebook.com/…", "instagram": "https://instagram.com/…" },
    "menu_categories": [
      { "id": 1, "name": "Welding Machines", "slug": "welding-machines" },
      { "id": 2, "name": "CNC Cutting Machines", "slug": "cnc-cutting-machines" }
    ]
  }
}
```
`social` only lists links that have been filled in. With none filled in, it is `{}`.

---

## 2. GET `/home`

Returns all homepage data in one call.

**Request:** `GET /api/home`

**Response 200**
```json
{
  "success": true,
  "message": "OK",
  "data": {
    "hero_slides": [
      { "title": "Powering Industrial Performance.", "subtitle": "Reliable welding and cutting solutions…", "button_text": "Explore Products", "button_link": "/products", "image": null }
    ],
    "about": {
      "since": 1981,
      "title": "Built On Experience. Driven By Trust.",
      "description": "Sanjay Sales is a distributor, exporter and importer of…",
      "highlights": [
        { "value": "40+", "label": "Years of Industry Experience" },
        { "value": "Gujarat", "label": "Strong Distribution Presence" }
      ]
    },
    "categories": [
      { "id": 1, "name": "Welding Machines", "slug": "welding-machines", "description": null, "image_url": "https://…/uploads/categories/x.jpg", "sort_order": 1, "products_count": 3 }
    ],
    "featured_products": [
      { "id": 1, "name": "MIG Welding Machine", "slug": "mig-welding-machine", "brand": "ESAB", "price": null, "short_description": "Inverter based MIG welder…", "image_url": "https://…/uploads/products/mig.jpg", "featured": true,
        "category": { "id": 1, "name": "Welding Machines", "slug": "welding-machines" } }
    ],
    "stats_section": { "title": "Numbers That Define Us", "subtitle": null },
    "stats": [
      { "value": 40, "suffix": "+", "label": "Years Experience" },
      { "value": 500, "suffix": "+", "label": "Products Supplied" },
      { "value": 1000, "suffix": "+", "label": "Satisfied Clients" },
      { "value": 25, "suffix": "+", "label": "Trusted Brands" }
    ],
    "clients": [ { "id": 1, "name": "ABC Ltd", "image_url": "https://…/uploads/clients/a.png" } ],
    "latest_blogs": [
      { "id": 1, "title": "The Future of Welding Technology…", "slug": "the-future-of-welding-technology", "excerpt": "…", "image_url": "https://…", "author": "Admin", "published_at": "2024-10-28", "published_date": "28 Oct 2024" }
    ]
  }
}
```
`featured_products` returns up to 8 products marked "Featured" in the admin. `latest_blogs` returns 3 posts.

---

## 3. GET `/categories`

Returns every active category with its active sub-categories and product counts. Use it for the menu dropdown, the homepage grid and the products sidebar.

**Request:** `GET /api/categories`

**Response 200**
```json
{
  "success": true,
  "message": "OK",
  "data": [
    {
      "id": 1, "name": "Welding Machines", "slug": "welding-machines", "description": null,
      "image_url": null, "sort_order": 1, "products_count": 3,
      "subcategories": [
        { "id": 1, "category_id": 1, "name": "MIG Welding", "slug": "mig-welding", "description": null, "image_url": null, "sort_order": 0, "products_count": 1 }
      ]
    }
  ]
}
```

### GET `/categories/{slug}`
Returns one category in the same shape. Get its products with `/products?category={slug}`.
`GET /api/categories/welding-machines` returns `{ "success": true, "data": { …category…, "subcategories": [ … ] } }`

### GET `/categories/{categorySlug}/{subcategorySlug}`
Returns one sub-category together with its parent category.
`GET /api/categories/welding-machines/mig-welding`
```json
{ "success": true, "message": "OK",
  "data": { "id": 1, "category_id": 1, "name": "MIG Welding", "slug": "mig-welding", "description": null, "image_url": null, "sort_order": 0, "products_count": 1,
            "category": { "id": 1, "name": "Welding Machines", "slug": "welding-machines" } } }
```

### GET `/subcategories?category={slug|id}`
Returns a flat list of sub-categories. The `category` filter is optional. Each item has the same shape as above.

---

## 4. GET `/products`

Returns a paginated product list for the products page.

| Query param | Example | Notes |
|---|---|---|
| `category` | `welding-machines` | slug or id |
| `subcategory` | `mig-welding` | slug or id |
| `featured` | `1` | featured products only |
| `q` | `esab` | searches name, brand and short description |
| `sort` | `latest` | `latest` (default), `oldest`, `name_asc`, `name_desc` |
| `per_page` | `12` | default 12, maximum 100 |
| `page` | `1` | |

**Request:** `GET /api/products?category=welding-machines&sort=name_asc&per_page=12&page=1`

**Response 200**
```json
{
  "success": true,
  "message": "OK",
  "data": [
    {
      "id": 1, "name": "MIG Welding Machine", "slug": "mig-welding-machine", "brand": "ESAB", "price": null,
      "short_description": "Inverter based MIG welder for fabrication.",
      "image_url": "https://…/uploads/products/mig.jpg",
      "featured": true,
      "category": { "id": 1, "name": "Welding Machines", "slug": "welding-machines" },
      "subcategory": { "id": 1, "name": "MIG Welding", "slug": "mig-welding" }
    }
  ],
  "meta": { "current_page": 1, "per_page": 12, "total": 3, "last_page": 1, "from": 1, "to": 3 }
}
```
`image_url` is the product's first image, or `null` if it has none. A `price` of `null` means "Price on request".

**Response 422** (bad `sort` value)
```json
{ "success": false, "message": "The selected sort is invalid.", "errors": { "sort": ["The selected sort is invalid."] } }
```

---

## 5. GET `/products/{slug}`

Returns the full product for the detail page, plus 4 related products: same sub-category first, then the same category.

**Request:** `GET /api/products/cnc-plasma-cutting-machine`

**Response 200**
```json
{
  "success": true,
  "message": "OK",
  "data": {
    "product": {
      "id": 5, "name": "CNC Plasma Cutting Machine", "slug": "cnc-plasma-cutting-machine",
      "brand": null, "price": null,
      "short_description": "<p>High-performance <strong>CNC plasma cutter</strong> for metal fabrication…</p>",
      "short_description_text": "High-performance CNC plasma cutter for metal fabrication…",
      "image_url": "https://…/uploads/products/main.jpg",
      "featured": false,
      "category": { "id": 2, "name": "CNC Cutting Machines", "slug": "cnc-cutting-machines" },
      "subcategory": null,
      "description": "<p>HTML from the admin's rich-text editor…</p>",
      "images": [ { "id": 7, "url": "https://…/uploads/products/main.jpg" }, { "id": 8, "url": "https://…/uploads/products/2.jpg" } ],
      "features": "<ul><li><strong>High speed</strong> &amp; precision cutting</li><li>Heavy-duty structure</li></ul>",
      "applications": "<ul><li>Metal fabrication</li><li>Sheet metal industry</li></ul>",
      "specifications": "<h4>General</h4><figure class=\"table\"><table><tbody><tr><th>Machine Type</th><td>CNC Plasma Cutting Machine</td></tr></tbody></table></figure>",
      "faqs": [ { "question": "Which materials can it cut?", "answer": "<p>Mild steel, <em>stainless steel</em> and aluminium.</p>" } ],
      "updated_at": "2026-11-04T10:42:39+00:00"
    },
    "related_products": [
      { "id": 6, "name": "Fiber Laser Cutting Machine", "slug": "fiber-laser-cutting-machine", "brand": null, "price": null, "short_description": null, "image_url": "https://…", "featured": false,
        "category": { "id": 2, "name": "CNC Cutting Machines", "slug": "cnc-cutting-machines" } }
    ]
  }
}
```
`short_description`, `description`, `features`, `applications`, `specifications` and each FAQ `answer` are HTML from the admin's CKEditor (render as HTML; `null` when empty – hide that section). `faqs` is always an array, `[]` when there are none. `short_description_text` is the plain-text version for cards / meta tags.

**Response 404:** `{ "success": false, "message": "Record not found." }`

---

## 6. GET `/blogs`

Returns a paginated blog list. The default is 6 per page, matching the website's 2-page layout.

**Request:** `GET /api/blogs?page=1&per_page=6` (optional `q=welding` searches titles)

**Response 200**
```json
{
  "success": true,
  "message": "OK",
  "data": [
    { "id": 1, "title": "The Future of Welding Technology in Modern Manufacturing", "slug": "the-future-of-welding-technology-in-modern-manufacturing",
      "excerpt": "…", "image_url": "https://…/uploads/blogs/a.jpg", "author": "Admin",
      "published_at": "2024-10-28", "published_date": "28 Oct 2024" }
  ],
  "meta": { "current_page": 1, "per_page": 6, "total": 7, "last_page": 2, "from": 1, "to": 6 }
}
```

## 7. GET `/blogs/{slug}`

Returns one post plus 3 recent posts. Draft posts return 404.

**Response 200**
```json
{
  "success": true,
  "message": "OK",
  "data": {
    "blog": {
      "id": 1, "title": "…", "slug": "…", "excerpt": "…", "image_url": "https://…", "author": "Admin",
      "published_at": "2024-10-28", "published_date": "28 Oct 2024",
      "content": "<p>Full HTML content…</p>",
      "meta_title": "…", "meta_description": "…"
    },
    "recent_posts": [ { "id": 2, "title": "…", "slug": "…", "excerpt": "…", "image_url": "…", "author": "Admin", "published_at": "2024-10-22", "published_date": "22 Oct 2024" } ]
  }
}
```
If left empty in the admin, `meta_title` falls back to the title and `meta_description` falls back to the excerpt. Use them in Next.js `generateMetadata()`.

---

## 8. GET `/gallery` · GET `/videos` · GET `/clients`

```json
// GET /api/gallery  (Photo Gallery tab)
{ "success": true, "message": "OK", "data": [ { "id": 1, "title": "Industrial Facility", "image_url": "https://…/uploads/gallery/a.jpg" } ] }

// GET /api/videos  (Video Gallery tab)
{ "success": true, "message": "OK", "data": [ {
    "id": 1, "title": "Machine Demo",
    "youtube_url": "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
    "youtube_id": "dQw4w9WgXcQ",
    "embed_url": "https://www.youtube.com/embed/dQw4w9WgXcQ",
    "thumbnail_url": "https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg" } ] }

// GET /api/clients  ("Trusted By Industry Leaders")
{ "success": true, "message": "OK", "data": [ { "id": 1, "name": "ABC Ltd", "image_url": "https://…/uploads/clients/a.png" } ] }
```
All three are ordered by the admin's "Display Order" field.

## 9. GET `/catalogues`

Returns the PDF catalogues for the `/download` page. Manage them in the admin under **Catalogues**.

```json
{ "success": true, "message": "OK", "data": [
  { "id": 1, "title": "Welding Solutions",
    "file_url": "https://…/uploads/catalogues/Xy12….pdf",
    "file_type": "pdf", "file_size": 2500000, "file_size_label": "2.4 MB",
    "image_url": null }
] }
```
Link the button with `<a href={file_url} target="_blank" download>`.

---

## 10. POST `/inquiries` – Inquire Now form

The contact page and product pages both use this. Each submission is saved under **Admin › Inquiries** and e-mailed to `INQUIRY_EMAIL` (see Setup).

| Field | Rules |
|---|---|
| `name` | **required**, 2–150 characters |
| `email` | **required**, valid e-mail |
| `phone` | **required**, 7–20 characters of digits, spaces, `+ - ( )` |
| `subject` | optional, max 200 |
| `message` | **required**, 5–3000 characters |
| `product_id` | optional, the `id` of an active product. Send it when the form is on a product page. |
| `website` | **honeypot**. Render it as a hidden input and always leave it empty. Bots fill it in, and those submissions are not saved. |

**Request**
```http
POST /api/inquiries
Accept: application/json
Content-Type: application/json

{
  "name": "Ramesh Patel",
  "email": "ramesh@example.com",
  "phone": "+91 98765 43210",
  "subject": "Price of MIG welder",
  "message": "Please share price and delivery time.",
  "product_id": 1,
  "website": ""
}
```

**Response 201**
```json
{
  "success": true,
  "message": "Thank you! Your inquiry has been submitted. Our team will contact you soon.",
  "data": {
    "id": 1, "name": "Ramesh Patel", "email": "ramesh@example.com", "phone": "+91 98765 43210",
    "subject": "Price of MIG welder", "message": "Please share price and delivery time.",
    "product_id": 1, "source": "product", "created_at": "2026-11-04T10:43:40+00:00"
  }
}
```

**Response 422**
```json
{
  "success": false,
  "message": "Please enter your name.",
  "errors": {
    "name": ["Please enter your name."],
    "email": ["Please enter your email address."],
    "phone": ["Please enter your phone number."],
    "message": ["Please enter your message."]
  }
}
```
Show `errors.<field>[0]` under each input, or `message` as a single toast.

**Response 429:** more than 5 submissions in a minute from the same IP.

---

## Using it from Next.js

```js
// lib/api.js
const API = process.env.NEXT_PUBLIC_API_URL; // e.g. https://admin.sanjaysales.com/api

export async function api(path, { revalidate = 300, ...init } = {}) {
  const res = await fetch(`${API}${path}`, {
    headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
    next: { revalidate },          // ISR cache: 5 min
    ...init,
  });
  const json = await res.json();
  if (!json.success) throw Object.assign(new Error(json.message), { status: res.status, errors: json.errors });
  return json;                     // { data, meta }
}

// app/products/[slug]/page.js
//   const { data } = await api(`/products/${params.slug}`);   // data.product, data.related_products

// Contact form (client component). Post straight from the browser so the
// per-visitor rate limit uses the visitor's IP, not the Vercel server's.
//   await api('/inquiries', { method: 'POST', body: JSON.stringify(form), cache: 'no-store' });
```

Product and blog links should use the slug, as `/products/{slug}` and `/blog/{slug}`. Right now every card links to the same `/productdetail` and `/blogdetail` page.

---

## Setup (existing installation)

1. Upload the changed files.
2. Update the database in one of two ways:
   - run `php artisan migrate`, **or**
   - import `database/sanjay_sales_upgrade_3.sql` in phpMyAdmin, once.
3. Add the following to `.env` as needed, then run `php artisan config:clear`:
   ```
   INQUIRY_EMAIL=san_shah120@yahoo.com     # where inquiries are e-mailed (empty = don't e-mail)
   MAIL_MAILER=smtp                         # plus MAIL_HOST / MAIL_PORT / MAIL_USERNAME / MAIL_PASSWORD / MAIL_FROM_ADDRESS
   SITE_PHONE_1="+91 98250 47028"
   SITE_PHONE_2="+91 93749 91103"
   SITE_EMAIL_1=san_shah120@yahoo.com
   SITE_WHATSAPP=919825047028
   SOCIAL_FACEBOOK=https://facebook.com/...
   SOCIAL_INSTAGRAM=https://instagram.com/...
   STAT_YEARS=40
   STAT_PRODUCTS=500
   STAT_CLIENTS=1000
   STAT_BRANDS=25
   ```
   The hero slides, the about text and anything else on the website are in `config/site.php`.
4. In the admin, fill in the new product fields (**Key Features, Applications, Technical Specifications, FAQ**), upload catalogues under **Catalogues**, and read submissions under **Inquiries**.
