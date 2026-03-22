document.addEventListener('DOMContentLoaded', function(){
  const checkboxes = document.querySelectorAll('.product-checkbox');
  const previewList = document.getElementById('preview-list');
  const previewPrice = document.getElementById('preview-price');

  function updatePreview(){
    const selected = [];
    let total = 0;
    checkboxes.forEach(cb => {
      if (cb.checked){
        const label = cb.parentElement.textContent.trim();
        selected.push(label);
        total += parseFloat(cb.dataset.price || 0);
      }
    });
    previewList.textContent = selected.length ? selected.join(', ') : '–';
    previewPrice.textContent = total.toFixed(2);
  }

  checkboxes.forEach(cb => cb.addEventListener('change', updatePreview));
  updatePreview();
});
