let tagForms = null;
let tagFormLen = 1;

let imageForms = null;
let imageFormLen = 1;


let checkCount = 0;

let tagCheckBoxs = document.getElementsByClassName('tag');

for (let ti = 0; ti < tagCheckBoxs.length; ti++)
{
  if (tagCheckBoxs[ti].checked == true)
  {
    checkCount++;
  }
  
  console.log(checkCount);
}

imageFormLen = minImageFormLen = document.getElementsByClassName('imageForm').length;
tagFormLen = minTagFormLen =  document.getElementsByClassName('tagForm').length;

const maxImageFormLen = 5;
const maxTagFormLen = 5 - checkCount;

document.getElementById('addImageButton').addEventListener('click',function(event){
  event.preventDefault();

  const parent = document.getElementById('image-parent');

  // input要素を生成
  const newForm = document.createElement('input');

  newForm.name = 'new_image[]';

  // 新しく生成したinput要素のtypeをtextに設定
  newForm.type = 'file';

  // 新しく生成したinput要素のclassNameを設定
  newForm.className = 'imageForm w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out block';

  if (imageFormLen >= maxImageFormLen)
      return;

  imageFormLen+=1;

  parent.appendChild(newForm);
});

document.getElementById('delImageButton').addEventListener('click',function(event){

  event.preventDefault();

  const parent = document.getElementById('image-parent');
  imageForms = document.getElementsByClassName('imageForm');
  imageFormLen = imageForms.length;
    
  console.log(imageFormLen);
    
  if (imageFormLen <= minImageFormLen)
      return;

  parent.removeChild(imageForms[imageFormLen-1]);
});

document.getElementById('addTagButton').addEventListener('click',function(event){
  event.preventDefault();

  const parent = document.getElementById('tag-parent');

  // input要素を生成
  const newForm = document.createElement('input');

  newForm.name = 'new_tag[]';
  // 新しく生成したinput要素のtypeをtextに設定
  newForm.type = 'text';


  // 新しく生成したinput要素のclassNameを設定
  newForm.className = 'tagForm w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out block';

  if (tagFormLen >= maxTagFormLen)
      return;

  tagFormLen+=1;

  parent.appendChild(newForm);
});

document.getElementById('delTagButton').addEventListener('click',function(event){

  event.preventDefault();

  const parent = document.getElementById('tag-parent');
  tagForms = document.getElementsByClassName('tagForm');
  tagFormLen = tagForms.length;
    
  console.log(tagFormLen);
    
  if (tagFormLen <= minTagFormLen)
      return;

  parent.removeChild(tagForms[tagFormLen-1]);
});



