<script>
  let { greeting = 'Dashboard' } = $props()
</script>

<section data-testid="dashboard-page">
  <h2>{greeting}</h2>
</section>