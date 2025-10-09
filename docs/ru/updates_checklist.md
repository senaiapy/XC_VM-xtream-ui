# ✅ Чеклист подготовки релиза XC\_VM

> Последовательно выполни все пункты перед публикацией обновления.

---

## 🔢 1. Обновить версию

* Установить **новое значение `XC_VM_VERSION`** в следующих файлах:

**Файлы для редактирования:**

```
src/www/constants.php  
src/www/stream/init.php  
src/player/functions.php
```

**Auto-update команда:**

```bash
find -type f -name "*.php" -exec sed -i \
"s/define('XC_VM_VERSION', '[0-9]\+\.[0-9]\+\.[0-9]\+');/define('XC_VM_VERSION', 'X.Y.Z');/g" {} \;
```

**Закоммитить изменения с сообщением:**

```bash
git add .
git commit -m "Bump version to X.Y.Z"
```

---

## 🧹 2. Удалённые файлы

* Выполнить команду:

  ```bash
  make delete_files_list
  ```
* Открыть файл `dist/deleted_files.txt`
* Для каждого пути из списка добавить в `src/includes/cli/update.php` **после комментария** `// Update checkpoint` следующий блок:

  ```php
  if (file_exists(MAIN_HOME . 'file_path')) {
      unlink(MAIN_HOME . 'file_path');
  }
  ```

---

## ⚙️ 3. Сборка архивов

* Последовательно выполнить команды:

  ```bash
  make lb
  make main
  make main_update
  make lb_update
  ```
* Убедиться, что созданы следующие файлы:

  * `dist/loadbalancer.tar.gz` — установочный архив LB
  * `dist/loadbalancer_update.tar.gz` — архив обновления LB
  * `dist/XC_VM.zip` — установочный архив MAIN
  * `dist/update.tar.gz` — архив обновления MAIN
  * `dist/hashes.md5` — файл с хеш-суммами

---

## 📝 4. Changelog

Сначала сгенерируйте файл с изменениями из git:
```bash
git log --pretty=format:"- %s (%h)" X.Y.Z..main > dist/changes.md
```

---

*   **Перейдите по ссылке и добавьте изменения текущего релиза:**
    [https://github.com/Vateron-Media/XC_VM_Update/blob/main/changelog.json](https://github.com/Vateron-Media/XC_VM_Update/blob/main/changelog.json)

* Добавить изменения текущего релиза в формате JSON:

  ```json
  [
    {
        "version": "X.Y.Z",
        "changes": [
          "Описание изменения 1",
          "Описание изменения 2"
        ]
    }
  ]
  ```

---

## 🚀 5. GitHub релиз

* Создать новый релиз на [GitHub Releases](https://github.com/Vateron-Media/XC_VM/releases)
* Прикрепить следующие файлы к релизу:

  * `dist/loadbalancer.tar.gz`
  * `dist/XC_VM.zip`
  * `dist/update.tar.gz`
  * `dist/loadbalancer_update.tar.gz`
  * `dist/hashes.md5`
* Указать changelog в описании релиза

---

