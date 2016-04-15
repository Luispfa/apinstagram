{literal}
    <style>
        ul{
            width:{/literal}{$width}{literal};
            list-style-type: none;
            list-style-position: none;
            list-style-image: none;
            padding: 0;
        }
        li{
            background:green;
            float:left;
            padding: 0;
            width:calc(100%/{/literal}{$column}{literal});
            background: #fff;
            text-align: center;
        }
        li:nth-child({/literal}{$column}{literal}n){
            padding-right:0;
        }
        li a img{
            xmax-width: 150px;
            width: 97%;
        }
        p{
            width: 100%;
            float: left;
        }
    </style>
{/literal}
<aside class="" id="">
    <h2 class="widget-title">En Instagram</h2>
    <ul class="">
        {foreach from=$data item=dat}
            <li class="">
                <a class="" target="_blank" href="{$dat->Link}">
                    <img class="" title="{$dat->title|escape}" alt="{$dat->title|escape}" src="{$dat->Thumbnail}" style="max-width: {$dat->Thumbnail_width}px;">
                </a>
            </li>
        {/foreach}
    </ul>
    <p class=""><a target="_blank" rel="me" href="//instagram.com/{$username}">Síguenos</a></p>
</aside>
