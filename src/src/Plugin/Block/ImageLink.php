<?php
namespace Drupal\image_link\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides an Image Link block.
 *
 * @Block(
 *   id = "image_link",
 *   admin_label = @Translation("Image Link"),
 *   category = @Translation("Custom"),
 * )
 */
class ImageLink extends BlockBase implements BlockPluginInterface {

	/**
	 * {@inheritdoc}
	 */
	public function build() {
		$config = $this->getConfiguration();
		
		if (!empty($config['image_link_image'])) {
			$image = $config['image_link_image'];
		} else {
			$image = 'no image';
		}
		
		return array(
			'#image' => $image,
			'#link' => "Image link URL",
			'#theme' => 'image_link_block',
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Drupal\Core\Block\BlockBase::blockForm()
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$form = parent::blockForm($form, $form_state);
		$config = $this->getConfiguration();
		
		$form['image'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Image'),
			'#description' => $this->t('The image to be displayed as the link'),
			'#default_value' => isset($config['image_link_image']) ? $config['image_link_image'] : '',
		);
		return $form;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Drupal\Core\Block\BlockBase::blockSubmit()
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		parent::blockSubmit($form, $form_state);
		$values = $form_state->getValues();
		$this->configuration['image_link_image'] = $values['image'];
	}
}
?>