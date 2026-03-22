document.addEventListener('DOMContentLoaded', ()=>{
  const previewList = document.getElementById('preview-list');
  const previewPrice = document.getElementById('preview-price');
  const previewImage = document.getElementById('preview-image');
  const previewBoxSize = document.getElementById('preview-box-size');
  const previewBoxStyle = document.getElementById('preview-box-style');
  const ribbonInput = document.getElementById('ribbon_color_input');

  function getConfigFromPage(){
    const cfg = {};
    const boxSizeEl = document.querySelector('[name="box_size"]');
    const boxStyleEl = document.querySelector('[name="box_style"]');
    if(boxSizeEl) cfg.box_size = boxSizeEl.value;
    if(boxStyleEl) cfg.box_style = boxStyleEl.value;

    const messageEl = document.querySelector('[name="message"]');
    if(messageEl) cfg.message = messageEl.value;

    const packagingEl = document.querySelector('[name="packaging"]');
    if(packagingEl) cfg.packaging = packagingEl.value;

    const ribbonEl = document.querySelector('[name="ribbon_color"]') || ribbonInput;
    if(ribbonEl) cfg.ribbon_color = ribbonEl.value;

    // products from checkboxes
    const products = [];
    document.querySelectorAll('.product-checkbox:checked').forEach(cb=>{
      products.push({id:cb.value,name:cb.dataset.name||cb.parentElement.textContent.trim(),price:parseFloat(cb.dataset.price||0)});
    });
    
    // if no checked products, try window.previousProducts (injected from PHP on step 3)
    if(products.length === 0 && window.previousProducts) {
      cfg.products = window.previousProducts;
      return cfg;
    }
    
    cfg.products = products;
    return cfg;
  }

  function updateRibbonColor(color){
    // Update SVG ribbon color via getElementById
    const ribbonVertical = document.getElementById('ribbon-vertical');
    const ribbonHorizontal = document.getElementById('ribbon-horizontal');
    const bowLeft = document.getElementById('bow-left');
    const bowRight = document.getElementById('bow-right');
    const knot = document.getElementById('knot');
    
    if(ribbonVertical) ribbonVertical.setAttribute('fill', color);
    if(ribbonHorizontal) ribbonHorizontal.setAttribute('fill', color);
    if(bowLeft) bowLeft.setAttribute('fill', color);
    if(bowRight) bowRight.setAttribute('fill', color);
    if(knot) knot.setAttribute('fill', color);
  }

  function updateBoxColors(boxStyle){
    // Update box colors based on style
    const box = document.getElementById('box');
    const lid = document.getElementById('lid');

    const colors = {
      'Weihnachten': { box: '#e7f4e3', boxStroke: '#10b981', lid: '#c8e6c0' },
      'Geburtstag': { box: '#ffe4f5', boxStroke: '#ec4899', lid: '#ffc0e0' },
      'Neutral': { box: '#f3f4f6', boxStroke: '#9ca3af', lid: '#e5e7eb' }
    };

    const colorSet = colors[boxStyle] || colors['Neutral'];

    if(box) {
      box.setAttribute('fill', colorSet.box);
      box.setAttribute('stroke', colorSet.boxStroke);
    }
    if(lid) lid.setAttribute('fill', colorSet.lid);
  }

  function updatePreview(){
    const cfg = getConfigFromPage();
    // image selection basic: change URL by style
    if(previewImage){
      previewImage.src = '/assets/images/box.svg';
      previewImage.alt = (cfg.box_style || 'Box') + ' Vorschau';
    }
    if(previewBoxSize) previewBoxSize.textContent = cfg.box_size || '-';
    if(previewBoxStyle) previewBoxStyle.textContent = cfg.box_style || '-';

    // Update box colors based on style
    if(cfg.box_style) updateBoxColors(cfg.box_style);

    if(previewList){
      if(cfg.products && cfg.products.length) previewList.innerHTML = cfg.products.map(p=>`<div>${escapeHtml(p.name)} — €${p.price.toFixed(2)}</div>`).join('');
      else previewList.textContent = '–';
    }
    if(previewPrice){
      const total = (cfg.products||[]).reduce((s,p)=>s+(p.price||0),0);
      previewPrice.textContent = total.toFixed(2);
    }

    // Update ribbon color in SVG
    if(cfg.ribbon_color) updateRibbonColor(cfg.ribbon_color);
  }

  function escapeHtml(unsafe){
    return unsafe.replace(/[&<"'`]/g, function(m){return {'&':'&amp;','<':'&lt;','"':'&quot;',"'":'&#039;',"`":'&#x60;'}[m];});
  }

  // Handle ribbon color buttons
  document.querySelectorAll('.ribbon-color-btn').forEach(btn=>{
    btn.addEventListener('click', function(e){
      e.preventDefault();
      const color = this.getAttribute('data-color');
      if(ribbonInput) ribbonInput.value = color;
      
      // Update active state
      document.querySelectorAll('.ribbon-color-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      
      updatePreview();
    });
  });

  // attach listeners
  document.querySelectorAll('input, select, textarea').forEach(el=>{
    el.addEventListener('change', updatePreview);
    el.addEventListener('input', updatePreview);
  });

  // for dynamic checkboxes that may be loaded, delegate click
  document.addEventListener('change', function(e){ if(e.target && e.target.classList && e.target.classList.contains('product-checkbox')) updatePreview(); });

  // Set initial active color
  if(ribbonInput) {
    const initialColor = ribbonInput.value;
    const initialBtn = document.querySelector(`.ribbon-color-btn[data-color="${initialColor}"]`);
    if(initialBtn) initialBtn.classList.add('active');
  }

  // initial update
  updatePreview();
});

