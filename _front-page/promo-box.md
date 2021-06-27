---
title: Promo-box
front-page: promo-box
---
{% capture time_seed %}{{ 'now' | date: "%s" }}{% endcapture %}
{% assign random = time_seed | times: 1103515245 | plus: 12345 | divided_by: 65536 | modulo: 32768 | modulo: 10 %}
{% for post in site.tags.korabli limit: 1 offset: random %}

### {{ post.title }}
{{ post.excerpt }}

[Подробнее]({{ post.url }})

{% endfor %}
