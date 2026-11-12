# Sanjay Sales API

Base URL: `https://your-domain.com/api`
Method: **POST** for every endpoint. Parameters go in the JSON body, never in the URL.
Headers: `Content-Type: application/json`, `Accept: application/json`

Success: `{ "success": true, "message": "OK", "data": ... }`  (no pagination anywhere – lists return all records)
Error (422/404/429): `{ "message": "...", "errors": { "field": ["..."] } }`

---
## 1. POST /api/settings
Request: `{}`
Response data: `name`, `tagline`, `contact{...,whatsapp_link}`, `social{}`, `menu_categories[{id,name,slug}]`

## 2. POST /api/home
Request: `{}`
Response data: `hero_slides[]`, `about{}`, `stats[]`, `categories[]`, `featured_products[]` (product cards), `clients[]`, `latest_blogs[]` (blog cards)

## 3. POST /api/categories
Request: `{}`
```json
{ "success": true, "message": "OK", "data": [
  { "id": 1, "name": "Welding Machines", "slug": "welding-machines", "description": "...",
    "image_url": "https://your-domain.com/uploads/categories/a.jpg", "sort_order": 0, "products_count": 12,
    "subcategories": [ { "id": 3, "category_id": 1, "name": "Arc Welders", "slug": "arc-welders", "description": null, "image_url": null, "sort_order": 0, "products_count": 5 } ] } ] }
```

## 4. POST /api/categories/detail
Request: `{ "slug": "welding-machines" }`
Response: `data` = one category object (same shape as above).

## 5. POST /api/subcategories
Request (optional, slug or id): `{ "category": "welding-machines" }`
```json
{ "success": true, "message": "OK", "data": [
  { "id": 3, "category_id": 1, "name": "Arc Welders", "slug": "arc-welders", "description": null, "image_url": null, "sort_order": 0, "products_count": 5,
    "category": { "id": 1, "name": "Welding Machines", "slug": "welding-machines" } } ] }
```

## 6. POST /api/subcategories/detail
Request: `{ "category": "welding-machines", "slug": "arc-welders" }`
Response: `data` = one subcategory object.

## 7. POST /api/products
Request (all optional):
```json
{ "category": "welding-machines", "subcategory": "arc-welders", "featured": true, "q": "esab", "sort": "latest" }
```
`sort`: latest | oldest | name_asc | name_desc
```json
{ "success": true, "message": "OK", "data": [
  { "id": 5, "name": "MIG 350", "slug": "mig-350", "brand": "ESAB", "price": "₹12,500", "short_description": "...",
    "image_url": "https://your-domain.com/uploads/products/x.jpg", "featured": true,
    "category": { "id": 1, "name": "Welding Machines", "slug": "welding-machines" },
    "subcategory": { "id": 3, "name": "Arc Welders", "slug": "arc-welders" } } ] }
```

## 8. POST /api/products/detail
Request: `{ "slug": "mig-350" }`
```json
{ "success": true, "message": "OK", "data": {
  "product": { "id": 5, "name": "MIG 350", "slug": "mig-350", "brand": "ESAB", "price": "₹12,500", "short_description": "...", "image_url": "...", "featured": true,
    "category": { "id": 1, "name": "...", "slug": "..." }, "subcategory": { "id": 3, "name": "...", "slug": "..." },
    "description": "<p>...</p>", "images": [ { "id": 9, "url": "..." } ],
    "features": ["High speed"], "applications": ["Fabrication"],
    "specifications": [ { "group": "General", "items": [ { "label": "Type", "value": "MIG" } ] } ],
    "faqs": [ { "question": "Warranty?", "answer": "1 year" } ], "updated_at": "2024-10-28T10:00:00+05:30" },
  "related_products": [ { "id": 6, "name": "...", "slug": "...", "brand": "...", "price": null, "short_description": "...", "image_url": "...", "featured": false, "category": { "id": 1, "name": "...", "slug": "..." } } ] } }
```

## 9. POST /api/blogs
Request (optional): `{ "q": "welding" }`
```json
{ "success": true, "message": "OK", "data": [
  { "id": 1, "title": "Welding tips", "slug": "welding-tips", "excerpt": "...", "image_url": "...", "author": "Admin", "published_at": "2024-10-28", "published_date": "28 Oct 2024" } ] }
```

## 10. POST /api/blogs/detail
Request: `{ "slug": "welding-tips" }`
```json
{ "success": true, "message": "OK", "data": {
  "blog": { "id": 1, "title": "...", "slug": "...", "excerpt": "...", "image_url": "...", "author": "Admin", "published_at": "2024-10-28", "published_date": "28 Oct 2024",
            "content": "<h2>...</h2>", "meta_title": "...", "meta_description": "..." },
  "recent_posts": [ { "id": 2, "title": "...", "slug": "...", "excerpt": "...", "image_url": "...", "author": "...", "published_at": "2024-10-20", "published_date": "20 Oct 2024" } ] } }
```

## 11. POST /api/gallery   – Request `{}`
`data: [ { "id": 1, "title": "Workshop", "image_url": "..." } ]`

## 12. POST /api/videos   – Request `{}`
`data: [ { "id": 1, "title": "Demo", "youtube_url": "...", "youtube_id": "XXXXXXXXXXX", "embed_url": "https://www.youtube.com/embed/XXXXXXXXXXX", "thumbnail_url": "https://img.youtube.com/vi/XXXXXXXXXXX/hqdefault.jpg" } ]`

## 13. POST /api/clients   – Request `{}`
`data: [ { "id": 1, "name": "Tata", "image_url": "..." } ]`

## 14. POST /api/catalogues   – Request `{}`
`data: [ { "id": 1, "title": "Welding Solutions", "file_url": "...pdf", "file_type": "pdf", "file_size": 2516582, "file_size_label": "2.4 MB", "image_url": "..." } ]`

## 15. POST /api/inquiries  (5 per minute per IP)
Request:
```json
{ "name": "Rahul Shah", "email": "rahul@example.com", "phone": "+91 98765 43210", "subject": "Price request",
  "message": "Please share the price for MIG 350.", "product_id": 5, "website": "" }
```
`subject`, `product_id` optional. `website` is a honeypot: keep hidden and empty.

Response 201:
```json
{ "success": true, "message": "Thank you! Your inquiry has been submitted. Our team will contact you soon.",
  "data": { "id": 10, "name": "Rahul Shah", "email": "rahul@example.com", "phone": "+91 98765 43210", "subject": "Price request",
            "message": "Please share the price for MIG 350.", "product_id": 5, "source": "product", "created_at": "2024-10-28T10:15:00+05:30" } }
```
Response 422: `{ "message": "Please enter your name.", "errors": { "name": ["Please enter your name."] } }`
