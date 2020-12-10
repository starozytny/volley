import React from 'react';

export function UserItems (props) {
    const { data } = props

    return <div className="items items-user">
        {data.map(elem => {
            return <div className="item" key={elem.id}>

                <div className="item-selector" />

                <div className="item-content">

                    <div className="item-body">
                        <div className="avatar">
                            <img src={`https://robohash.org/${elem.username}?size=64x64`} alt={`Avatar de ${elem.username}`}/>
                        </div>
                        <div className="infos">
                            <div>
                                <div className="username">{elem.username}</div>
                                <div className="email">{elem.email}</div>
                            </div>
                            <div className="actions">
                                <div className="btn-icon">
                                    <span className="icon-pencil" />
                                    <span className="tooltip">Modifier</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        })}
    </div>
}