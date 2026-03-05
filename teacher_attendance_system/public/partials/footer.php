</main>

<footer class="py-4">
  <div class="container small-muted">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>© <?= date('Y') ?> <?= h(APP_NAME) ?> · File-based storage</div>
      <div>Department restriction: <span class="badge text-bg-light"><?= h(ALLOWED_DEPARTMENT) ?></span></div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
