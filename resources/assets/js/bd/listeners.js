import Bus from '../Bus';

window.addEventListener('dragover',function(e){
  Bus.$emit('dragover',e);
  e.preventDefault();
},false);
window.addEventListener('keydown', e => {
  Bus.$emit('keydown', e);
});
window.addEventListener('dragenter', e => {
  Bus.$emit('dragenter',e);
});
window.addEventListener('dragleave', e => {
  Bus.$emit('dragleave', e);
});
window.addEventListener('drop', e => {
  e.preventDefault();
  return false;
});

document.addEventListener('click', e => Bus.$emit('click'));
document.addEventListener('mouseup', e => Bus.$emit('mouseup'));
document.addEventListener('mousemove', e => Bus.$emit('mousemove', e.pageX, e.pageY, e));