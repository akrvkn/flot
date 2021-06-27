---
title: Intro
front-page: home-top
---
{% for cat in site.categories %}
{% if cat[0] == 'lenta' %}

{% for post in cat[1] limit: 1 %}
### {{ post.title }}

{{ post.content }}

[Подробнее]({{ post.url }})

****** 

{% endfor %}

{% endif %}
{% endfor %}
