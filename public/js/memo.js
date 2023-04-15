var tagForms = null;
var tagFormLen = 1;

var imageForms = null;
var imageFormLen = 1;

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

    if (imageFormLen >= 5)
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
    
    if (imageFormLen <= 1)
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

    if (tagFormLen >= 5)
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
    
    if (tagFormLen <= 1)
        return;

    parent.removeChild(tagForms[tagFormLen-1]);
  });



// function addTagForm(event)
// {
//     event.preventDefault();
//     tagForms = document.getElementsByClassName('tagForm');
//     tagFormLen = tagForms.length;

//     if (tagFormLen >= 5)
//         return;

//     var newForm = document.createElement('input');
//     newForm.type = 'text';
//     newForm.className = 'tagForm w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out block';
    
//     var parent = document.getElementById('tag-parent');

//     parent.appendChild(newForm);
// }

// function delTagForm(event)
// {
//     event.preventDefault();
//     tagForms = document.getElementsByClassName('tagForm');
//     tagFormLen = tagForms.length;

//     if (tagFormLen <= 1)
//         return;

//     var parent = document.getElementById('tag-parent');

//     parent.removeChild(tagForms[tagFormLen-1]);
// }

// function addImageForm(event)
// {
//     event.preventDefault();
//     imageForms = document.getElementsByClassName('imageForm');
//     imageFormLen = imageForms.length;

//     if (imageFormLen >= 5)
//         return;

//     var newForm = document.createElement('input');
//     newForm.type = 'text';
//     newForm.className = 'imageForm w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out block';
    
//     var parent = document.getElementById('image-parent');

//     parent.appendChild(newForm);
// }

// function delImageForm(event)
// {
//     event.preventDefault();
//     imageForms = document.getElementsByClassName('imageForm');
//     imageFormLen = imageForms.length;

//     if (imageFormLen <= 1)
//         return;

//     var parent = document.getElementById('image-parent');

//     parent.removeChild(imageForms[imageFormLen-1]);
// }