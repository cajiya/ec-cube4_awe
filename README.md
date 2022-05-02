# EC-CUBE4.1用 AttachWysiwygEditorプラグイン

管理画面のURL(PATH)と、textareaのSELECTORを設定することで、
管理画面内の任意のテキストエリアをWysiwigEditor化することができるプラグイン。


# インストール方法

```
cd app/Plugin;
git clone https://github.com/cajiya/ec-cube4_awe.git;
mv ec-cube4_awe AttachWysiwygEditor;
cd ../../;
php bin/console eccube:plugin:install --code="AttachWysiwygEditor"
```

