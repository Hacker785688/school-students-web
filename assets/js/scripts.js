function showAll(btn){
    let boxes = btn.parentElement.querySelectorAll('.message-box.hidden');
    boxes.forEach(b=>b.classList.remove('hidden'));
    btn.style.display='none';
}
