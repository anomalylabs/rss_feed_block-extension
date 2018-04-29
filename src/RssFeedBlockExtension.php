<?php namespace Anomaly\RssFeedBlockExtension;

use Anomaly\BlocksModule\Block\BlockExtension;
use Anomaly\BlocksModule\Block\Contract\BlockInterface;
use Anomaly\RssFeedBlockExtension\Command\FetchCurlContent;
use Anomaly\RssFeedBlockExtension\Command\FetchRawContent;

/**
 * Class RssFeedBlockExtension
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class RssFeedBlockExtension extends BlockExtension
{

    /**
     * This extension provides an RSS
     * feed block for the blocks module.
     *
     * @var null|string
     */
    protected $provides = 'anomaly.module.blocks::block.rss_feed';

    /**
     * The block view.
     *
     * @var string
     */
    protected $view = 'anomaly.extension.rss_feed_block::content';

    /**
     * Fired just before rendering.
     *
     * @param BlockInterface $block
     * @param \SimplePie     $rss
     */
    public function onLoad(BlockInterface $block, \SimplePie $rss)
    {
        $items = $block->cache(
            __METHOD__ . '_' . $block->getId(),
            30,
            function () use ($rss, $block) {

                try {

                    /**
                     * This should work and is more SSL friendly
                     * with providers like CloudFlare but can
                     * be disabled by security in some cases.
                     */
                    return $this->dispatch(new FetchRawContent($block));
                } catch (\Exception $e) {

                    try {

                        /**
                         * If security is disabling file_get_contents
                         * then this way should work fine unless
                         * there is an SSL / TLS issue.
                         */
                        return $this->dispatch(new FetchCurlContent($block));
                    } catch (\Exception $e) {

                        /**
                         * If everything above fails then we have
                         * an issue. Return false to let us know.
                         */
                        return false;
                    }
                }
            }
        );

        $this->block->addData('items', $items);
    }

}
