const option = document.getElementById('option');
const completed = document.getElementById('completed');

option.addEventListener('change',(e) => {
  if (e.target.value == 'finished') {
    completed.style.display = 'block';
  } else {
    completed.style.display = 'none';
  }
})