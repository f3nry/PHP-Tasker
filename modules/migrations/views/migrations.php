<html>
  <body>
    <style type="text/css">
    body {
      background-color: #E87693;
    }
    </style>

    <h1>Oops, you have a new migration!</h1>

    <h2>To version <?= $migration->get_version() ?></h2>

    <code><?= nl2br($migration->get_up_sql()) ?></code>

    <form action="<?= URL::site('migrations/execute/' . $migration->get_version(), null, false) ?>" method="get">
      <input type="hidden" name="redirect" value="<?= Request::current()->referrer() ?>" />
      <input type="submit" value="Execute" />
    </form>
  </body>
</html>
