<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Add-on Web-App">
    <meta name="base" content="<? echo(BASE) ?>">
    <title>Blog</title>
    <link href="<? route('css/bs.css') ?>" rel="stylesheet">
    <link href="<? route('css/style.css') ?>" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="/blog">Info_Blog</a>
    </nav>
    <main role="main">
      <div class="welcome-area">
        <div class="container">
          <a href="/blog" style="color:#333;text-decoration: none"><h3 class="display-5">Info_Blog</h3></a>
          <p style="max-width: 700px;">Some web programmers obtain four-year university degrees, while others gain all the knowledge they need from technical school. Essentially, programmers need to know all about programs that they will be using. In short, companies will not hire programmers who are not schooled in the programs that a company wishes to use. Therefore, a programmer's education is never quite complete.</p>
          <p><a class="btn btn-danger btn-sm" href="/blog/create" role="button">Add Article &raquo;</a></p>
        </div>
      </div>
