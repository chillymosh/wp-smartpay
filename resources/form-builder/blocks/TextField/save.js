import { __ } from '@wordpress/i18n'

export const save = ({ attributes }) => {
    return (
        <div className="form-element">
            <label for={attributes.attributes.name}>
                {attributes.settings.label}
            </label>
            <input
                type="text"
                className="form-control"
                id={attributes.attributes.name}
                name={`smartpay_text[${attributes.attributes.name}]`}
            />
        </div>
    )
}
