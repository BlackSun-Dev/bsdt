function formFocus(field){
  if (field.value == field.defaultValue){
    field.value ='';
  }
}

function formBlur(field){
  if (field.value == ''){
    field.value = field.defaultValue;
  }else{
  }
}