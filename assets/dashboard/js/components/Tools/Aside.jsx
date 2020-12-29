import React, {Component} from 'react';

export class Aside extends Component {
    constructor (props) {
        super(props)

        this.state = {
            active: false
        }

        this.handleOpen = this.handleOpen.bind(this)
        this.handleClose = this.handleClose.bind(this)
    }

    handleOpen = () => { this.setState({ active: true }) }
    handleClose = () => { this.setState({ active: false }) }

    render () {
        const { content, children } = this.props
        const { active } = this.state

        return <div className={`aside ${active}`}>
            <div className="aside-overlay" onClick={this.handleClose} />
            <div className="aside-content">
                <div className="aside-title">
                    <span className="title">{children}</span>
                    <span className="icon-cancel" onClick={this.handleClose} />
                </div>
                {content}
            </div>
        </div>
    }
}