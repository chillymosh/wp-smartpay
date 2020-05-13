jQuery(function ($) {

    /** ============= Files ============= **/

    /** Select file **/
    $(document.body).on('click', '#smartpay-metabox .upload-product-file', (e) => {
        e.preventDefault();

        uploadProductFiles()
    });

    /** Remove file **/
    $(document.body).on('click', '#smartpay-metabox .remove-file', (e) => {
        $filesItem = $(e.target).parents('.files-item')

        // Remove from variation files
        $variations = $(document.body).find('#smartpay-metabox .variation-option')

        $variations.each((index, element) => {
            removeVariationFile($(element), parseInt($filesItem.data('file-id')))
        })

        // Remove file item
        $filesItem.remove()

        toggleFileSelectBox()
    });

    /** Upload product file */
    function uploadProductFiles() {
        return new Promise((resolve) => {
            media = new SmartPayMediaSelector({
                multiple: true,
                title: 'Select files',
                select: function (selected_files) {
                    $productFiles = $('.product-files')

                    selected_files.forEach(file => {

                        // Check if file exist
                        fileExist = false
                        $productFiles.find('.files-item').each((index, item) => {
                            if (file.id == $(item).data('file-id')) {
                                fileExist = true
                            }
                        })

                        // If file id not found then append files item
                        if (!fileExist) {

                            file_icon = file.sizes ? file.sizes.thumbnail.url : file.icon;
                            id = file.id;

                            listItem = `<li class="list-group-item list-group-item-action mb-0 files-item" id="file-${id}" data-file-id="${id}">
                            <input type="hidden" class="form-control file-id" name="files[${id}][id]" value="${id}">
                            <input type="hidden" class="form-control file-icon" name="files[${id}][icon]" value="${file_icon}">
                            <input type="hidden" class="form-control file-filename" name="files[${id}][filename]" value="${file.filename}">
                            <input type="hidden" class="form-control file-mime" name="files[${id}][mime]" value="${file.mime}">
                            <input type="hidden" class="form-control file-size" name="files[${id}][size]" value="${file.filesizeHumanReadable}">
                            <input type="hidden" class="form-control file-url" name="files[${id}][url]" value="${file.url}">
                            <div class="d-flex">
                                <div class="file-type">
                                <img src="${file_icon}" alt="" width="28" height="28"/>
                                </div>
                                <div class="d-flex justify-content-between w-100">
                                    <div class="d-flex flex-column ml-3">
                                        <h5 class="file-name m-0">${file.filename}</h5>
                                        <h6 class="file-size text-muted m-0">${file.filesizeHumanReadable}</h6>
                                    </div>
                                    <div class="">
                                        <button type="button" class="btn btn-light btn-sm pb-0 border remove-file"><i data-feather="trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </li>`;

                            $productFiles.append(listItem)

                            // Append to variation files
                            $variations = $(document.body).find('#smartpay-metabox .variation-option')

                            $variations.each((index, element) => {
                                appendVariationFile($(element), file)
                            })
                        } else {
                            alert('File already added!');
                        }
                    });

                    feather.replace()

                    toggleFileSelectBox()

                    resolve(true);
                }
            });

            media.open();
        })
    }

    /** Toggle file select box **/
    function toggleFileSelectBox() {
        $productFiles = $('.product-files');
        if ($productFiles.children('.files-item').length) {
            $('.no-product-file-box').hide();
            $('.product-files-secion').show();
        } else {
            $('.product-files-secion').hide();
            $('.no-product-file-box').show();
        }
    }

    /** ============= Variations ============= **/

    /** Add variation **/
    $(document.body).on('click', '#smartpay-metabox .add-variation', (e) => {
        e.preventDefault()
        toggleProductHasVariation(true)

        if (!$('.variations .variation-option').length) {
            addVariationOption()
        }

    });

    /** Remove variation **/
    $(document.body).on('click', '#smartpay-metabox .remove-variation', (e) => {
        e.preventDefault()
        toggleProductHasVariation(false)
    });

    /** Add variation option **/
    $(document.body).on('click', '#smartpay-metabox .add-variation-option', (e) => {
        e.preventDefault()
        addVariationOption()

    });

    /** Remove variation option **/
    $(document.body).on('click', '#smartpay-metabox .remove-variation-option', (e) => {
        e.preventDefault()

        $(e.target).parents('.variation-option').remove()

        if (!$('.variations .variation-option').length) {
            toggleProductHasVariation(false)
        } else {
            scrollToLastVariationOption()
        }
    });

    /** Add variation option **/
    function addVariationOption() {

        $variations = $('#smartpay-metabox .variations')

        variationId = $('#smartpay-metabox .variations .variation-option').length + 1

        option = `<div class="variation-option" data-variation-id="${variationId}">
            <div class="variation-option__header p-3">
                <div class="form-row">
                    <div class="col-7">
                        <div class="form-group m-0">
                            <label for="variations[${variationId}][name]"
                                class="text-muted my-2 d-block"><strong>Option
                                    name</strong></label>
                            <input type="text" class="form-control"
                                id="variations[${variationId}][name]"
                                name="variations[${variationId}][name]"
                                placeholder="Option name">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group m-0">
                            <label for="variations[${variationId}][additional_amount]"
                                class="text-muted my-2 d-block"><strong>Additional amount</strong></label>
                            <input type="text"
                                name="variations[${variationId}][additional_amount]"
                                id="variations[${variationId}][additional_amount]"
                                class="form-control" placeholder="1.0">
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <div class="mt-4">
                            <button type="button" class="btn btn-light btn-sm border shadow-sm pb-0 ml-2 remove-variation-option"><i
                                    data-feather="trash" width="20" height="20"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="variation-option-body bg-light p-3">
                <div class="form-group">
                    <label for="variations[${variationId}][description]"
                        class="text-muted my-2 d-block"><strong>Description</strong></label>
                    <textarea class="form-control" id="variations[${variationId}][description]" name="variations[${variationId}][description]" rows="3"></textarea>
                </div>

                <!-- Files -->
                <label class="text-muted my-2 d-block"><strong>Files</strong></label>
                <div class="form-group no-variation-file-box">
                    <div class="border rounded text-center p-5">
                        <i data-feather="package" width="42" height="42"></i>
                        <h3 class="text-muted">Associate files with this variant</h3>
                        <button class="btn btn-light border shadow-sm select-variation-files">Select files</button>
                    </div>
                </div>
                <div class="variation-files-secion" style="display:none">
                    <ul class="list-group variation-files"></ul>
                </div>
            </div>
        </div>`;

        $variations.append(option)

        feather.replace()

        scrollToLastVariationOption()
    }

    /** Scroll to last option **/
    function scrollToLastVariationOption() {
        $('html, body').animate({
            scrollTop: eval($variations.children('.variation-option').last().offset().top - 70)
        }, 500);
    }

    /** Toggle product has variation **/
    function toggleProductHasVariation(hasVariation = false) {

        if (hasVariation) {
            $('.variations-secion').show();
            $('#has_variations').val('1');
            $('.no-variations-box').hide();
        } else {
            $('.no-variations-box').show()
            $('#has_variations').val('0')
            $('.variations-secion').hide()
        }
    }

    /** Select variation file **/
    $(document.body).on('click', '#smartpay-metabox .select-variation-files', async (e) => {
        e.preventDefault()

        $variationOption = $(e.target).parents('.variation-option')

        // Remove if it has files
        $variationOption.find('.variation-files').empty()

        hasFile = getProductFiles().length

        if (!hasFile) {
            alert('You have no file for this product, select a file first!')
            uploadProductFiles().then(hasFile => {
                if (hasFile) {
                    getProductFiles().forEach(file => {
                        appendVariationFile($variationOption, file)
                    })

                    // Show variation files
                    $variationOption.find('.no-variation-file-box').hide()
                    $variationOption.find('.variation-files-secion').show()
                }
            })
        } else {
            getProductFiles().forEach(file => {
                appendVariationFile($variationOption, file)
            })

            // Show variation files
            $variationOption.find('.no-variation-file-box').hide()
            $variationOption.find('.variation-files-secion').show()
        }

    });



    /** Check or unchecked files **/
    $(document.body).on('change', '.variation-file', (e) => {
        e.preventDefault()

        $variationOption = $(e.target).parents('.variation-option');
        totalCheckedItem = $(e.target).parents('.variation-files').find('.variation-file:checkbox:checked').length

        // Hide variation files
        if (!totalCheckedItem) {
            $variationOption.find('.variation-files-secion').hide()
            $variationOption.find('.no-variation-file-box').show()
        }

    });

    /** Toggle variation has files **/
    function toggleVariationHasFiles($variationOption) {
        if ($variationOption.find('.variation-files').children('.files-item').length) {
            $variationOption.find('.no-variation-file-box').hide()
            $variationOption.find('.variation-files-secion').show()
        } else {
            $variationOption.find('.variation-files-secion').hide()
            $variationOption.find('.no-variation-file-box').show()
        }
    }

    /** Get product files **/
    function getProductFiles() {
        files = [];
        $productFilesItems = $(document.body).find('#smartpay-metabox #product-files').children('.files-item')

        $productFilesItems.each((index, element) => {
            id = $(element).children('.file-id').val()
            icon = $(element).children('.file-icon').val()
            filename = $(element).children('.file-filename').val()
            mime = $(element).children('.file-mime').val()
            size = $(element).children('.file-size').val()
            url = $(element).children('.file-url').val()

            files.push({ id, icon, filename, mime, size, url })
        })

        return files
    }

    /** Append variation file **/
    function appendVariationFile($variationOption, file) {
        $variationFiles = $variationOption.find('.variation-files')

        // Check if file exist
        fileExist = false
        $variationFiles.find('.files-item').each((index, item) => {
            if (file.id == $(item).data('file-id')) {
                fileExist = true
            }
        })

        // If file id not found then append files item
        if (!fileExist) {
            variationId = $variationOption.data('variation-id')

            filesItem = `<li class="list-group-item m-0 d-flex justify-content-between files-item file-${file.id}" data-file-id="${file.id}">
                <div class="custom-checkbox custom-checkbox-round">
                    <input type="checkbox" class="custom-control-input variation-file" id="variations[${variationId}][files][${file.id}]" name="variations[${variationId}][files][${file.id}]" value="${file.id}">
                    <label class="custom-control-label" for="variations[${variationId}][files][${file.id}]">${file.filename}</label>
                </div>
            </li>`;

            $variationFiles.append(filesItem)
        }
    }

    /** Remove variation file **/
    function removeVariationFile($variationOption, fileId) {
        $variationFiles = $variationOption.find('.variation-files')

        // Check if file exist
        fileExist = false
        $variationFiles.find('.files-item').each((index, item) => {
            if (fileId == $(item).data('file-id')) {
                fileExist = true
            }
        })

        // If file id found then remove files item
        if (fileExist) {
            $variationFiles.find('.file-' + fileId).remove()
            toggleVariationHasFiles($variationOption)
        }
    }
});
