---
title: Home-bottom
front-page: home-bottom
---

{% for cat in site.categories %}
{% if cat[0] == 'news' %}

{% for post in cat[1] limit: 2 %}
### {{ post.title }}

{{ post.excerpt }}

[Подробнее]({{ post.url }})

****** 

{% endfor %}

{% endif %}
{% endfor %}
