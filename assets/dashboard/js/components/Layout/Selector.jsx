import React, { Component } from 'react';

import { Alert } from "@dashboardComponents/Tools/Alert";

export class Selector extends Component {
    constructor() {
        super();

        this.state = {
            isChecked: false
        }

        this.handleChange = this.handleChange.bind(this);
    }

    componentDidMount() {
        const { id, selectors } = this.props;

        let isChecked = false;
        if(selectors.length !== 0){
            selectors.map(el => {
                if(el === id){
                    isChecked = true;
                }
            })
        }

        if(isChecked){
            document.getElementById('item-selector-' + id).checked = true;
        }
        this.setState({ isChecked });
    }

    // from fo uncheck via toolbar filter checked
    handleChange = (e) => {
        let value = parseInt(e.currentTarget.value);
        let isChecked = !!(e.currentTarget.checked)

        this.setState({ isChecked });

        this.props.onSelectors(value, isChecked);
    }

    render () {
        const { id } = this.props;
        const { isChecked } = this.state;

        return <div className="selector">
            <input type="checkbox" name="item-selector" id={`item-selector-${id}`} value={id} onChange={this.handleChange}/>
            <label htmlFor={`item-selector-${id}`} className={`item-selector ${isChecked}`}/>
        </div>
    }
}