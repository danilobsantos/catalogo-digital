<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($urls as $u)
    <url>
        <loc>{{ $u['loc'] }}</loc>
        <lastmod>{{ $u['lastmod'] }}</lastmod>
        <changefreq>{{ $u['changefreq'] }}</changefreq>
        <priority>{{ $u['priority'] }}</priority>
        @foreach ($u['children'] ?? [] as $child)
            <image:image>
                <image:loc>{{ $child['image_loc'] }}</image:loc>
            </image:image>
        @endforeach
    </url>
@endforeach
</urlset>
