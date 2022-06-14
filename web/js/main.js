(function (max = 5, min = 2) {

        //Функция для блокирования нечекнутый чекбоксов
        let toDisableCheckboxes = function (checkboxes) {
            for (let j = 0; j < checkboxes.length; j++) {
                if (!checkboxes[j].checked) {
                    checkboxes[j].disabled = true;
                }
            }
        }

        //Фунция для разблокирования чекбоксов
        let toAbleCheckboxes = function (checkboxes) {
            for (let k = 0; k < checkboxes.length; k++) {
                if (checkboxes[k].disabled) {
                    checkboxes[k].disabled = false;
                }
            }
        }

        //Поиск элементов на странице
        let allCheckboxes = document.querySelectorAll('.ingredient-checkbox');
        let checkedCheckboxes = (document.querySelectorAll('.ingredient-checkbox:checked')).length;
        let searchRecipeButton = document.querySelector('#search-recipe-button');

        //Проверка состояния чекбоксов при загрузке страницы
        if (checkedCheckboxes >= max) {
            allCheckboxes.forEach((checkbox) => {
                toDisableCheckboxes(allCheckboxes);
            })
        }

        //Слушатель событий на чекбоксах
        allCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('click', function () {
                let checkedCheckboxes = (document.querySelectorAll('.ingredient-checkbox:checked')).length;
                if (checkedCheckboxes >= max) {
                    toDisableCheckboxes(allCheckboxes);
                } else {
                    toAbleCheckboxes(allCheckboxes);
                }
            })
        });

        //Слушатель событий на кнопке
        searchRecipeButton.addEventListener('click', function (e) {
            let checkedCheckboxes = (document.querySelectorAll('.ingredient-checkbox:checked')).length;
            if (checkedCheckboxes < min) {
                e.preventDefault();
                e.stopPropagation();
                alert('Выберите не меньше двух ингредиентов для поиска!');
            }
        })
    }


)();