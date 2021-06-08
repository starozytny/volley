import React, { Component } from "react";

import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import { Aside } from "@dashboardComponents/Tools/Aside";

export class Cookies extends Component {
    constructor(props) {
        super();

        this.state = {
            showCookie: true
        }

        this.aside = React.createRef();

        this.handleOpen = this.handleOpen.bind(this);
    }

    componentDidMount = () => {
        const { consent } = this.props;

        let cookies = document.cookie.split(';');
        let find = false;
        cookies.forEach(el => {
            if(el === consent+"=true" || el === consent+"=false"){
                find = true;
            }
        })

        if(find){
            this.setState({ showCookie: false })
        }
    }

    handleOpen = () => {
        this.aside.current.handleOpen();
    }

    render () {
        const { showCookie } = this.state;

        let settings = <div className="aside-cookies-choices">
            <div className="item">
                <span className="title">Cookies nécessaire</span>
                <p>
                    Les cookies nécessaires contribuent à rendre notre site internet utilisable.
                    Par exemple, pour la navigation entre les pages, l'accès aux zones sécurisées,
                    la mémorisation de votre choix d'acceptation des cookies etc.. <br/>
                    Notre site internet ne peut pas fonctionner sans ces cookies.
                </p>
            </div>
            <div className="item">
                <span className="title">Cookies statistiques</span>
                <p>
                    Les cookies statistiques via <b>Matomo</b> nous aident à mesurer l'audience et
                    comprendre comment les visiteurs interagissent avec notre site internet.
                    Ces données ne sont pas identifiable grâce à l'anonymisation de l'adresse IP.
                    De plus, la conservation des données est limitée à 13 mois maximum.
                    <br/>
                    Ces données ne sont pas partagées à un tiers.
                </p>
                <iframe className="matomo-iframe"
                    src="https://matomo.demodirecte.fr/index.php?module=CoreAdminHome&action=optOut&language=fr&backgroundColor=&fontColor=&fontSize=&fontFamily=Poppins"
                />
            </div>
        </div>

        return <>
            {showCookie &&<>
                <div className="cookies">
                    <div className="cookies-title">
                        <div className="biscuit">
                            <img src={'/build/app/images/biscuit.svg'} alt="Cookie illustration"/>
                        </div>
                        <div className="title">Nos cookies</div>
                        <div className="content">
                            Notre site internet utilise des cookies pour vous offrir la meilleure expérience utilisateur.
                            Plus d'informations dans notre <a href={Routing.generate("app_politique")}>politique de confidentialité</a>
                        </div>
                    </div>
                    <div className="cookies-choices">
                        <div onClick={this.handleOpen}>Paramétrer</div>
                        <div>Tout refuser</div>
                        <div>Tout accepter</div>
                    </div>
                </div>
            </>}
            <Aside ref={this.aside} content={settings}>Paramétrer nos cookies</Aside>
        </>
    }
}